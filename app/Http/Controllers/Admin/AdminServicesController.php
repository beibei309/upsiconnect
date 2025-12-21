<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceWarningMail;
use App\Mail\ServiceApprovedMail; 
use App\Mail\ServiceRejectedMail; 

// Import the Notification
use App\Notifications\ServiceStatusNotification;

// 👇 TAMBAH DUA LINE NI (Supaya boleh hantar email)
use App\Mail\WarningMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminServicesController extends Controller
{
   public function index(Request $request)
{
    $search     = $request->query('search');
    $categoryId = $request->query('category');
    $studentId  = $request->query('student');

    $services = StudentService::with(['user', 'category'])
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($categoryId, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($studentId, function ($query, $studentId) {
            $query->where('user_id', $studentId);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $categories = Category::orderBy('name')->get();
    $students   = User::where('role', 'student')->orderBy('name')->get();

    return view('admin.services.index', compact(
        'services',
        'categories',
        'students'
    ));
}


    // Approve a service
    public function approve(StudentService $service)
    {
        $service->approval_status = 'approved';
        $service->save();

        if ($service->user && $service->user->email) {
            Mail::to($service->user->email)->send(new ServiceApprovedMail($service));
        }

        // 2. Send Database Notification
        if ($service->user) {
            $service->user->notify(new ServiceStatusNotification('approved', $service));
        }

        return redirect()->route('admin.services.index')->with('success', 'Service approved.');
    }

    // Reject a service
    public function reject(StudentService $service)
    {
        $service->approval_status = 'rejected';
        $service->save();

        if ($service->user && $service->user->email) {
            Mail::to($service->user->email)->send(new ServiceRejectedMail($service));
        }

        // 2. Send Database Notification
        if ($service->user) {
            $service->user->notify(new ServiceStatusNotification('rejected', $service));
        }

        return redirect()->route('admin.services.index')->with('success', 'Service rejected.');
    }

    // Delete/Destroy service record
    public function destroy(StudentService $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service has been permanently deleted.');
    }

    // 👇 INI FUNCTION BARU UNTUK WARNING (Copy bahagian ini)
    public function storeWarning(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // 2. Cari Servis
        $service = StudentService::findOrFail($id);
        $student = $service->user; // Owner servis

        // 3. UPDATE DATA (Ikut migration member kau: warning_count & warning_reason)
        $service->warning_count = $service->warning_count + 1;
        $service->warning_reason = $request->reason;
        
        // Logic 3 Strike = Suspend
        // Kalau dah kena 3 kali warning, status tukar jadi 'suspended'
        if ($service->warning_count >= 3) {
            $service->approval_status = 'suspended'; // Tukar status approval
            // $service->is_active = false; // Boleh uncomment kalau nak matikan servis terus
        }
        
        $service->save(); // Simpan perubahan

        // 4. Hantar Email ke Student
        try {
            // Data untuk dihantar ke dalam email
            $emailData = [
                'student_name' => $student->name,
                'service_name' => $service->title,
                'reason' => $request->reason,
                'count' => $service->warning_count
            ];

            // Hantar email guna WarningMail
            Mail::to($student->email)->send(new WarningMail($emailData));
            
        } catch (\Exception $e) {
            // Kalau email error, kita log error tu tapi tak stopkan sistem
            Log::error('Email warning gagal dihantar: ' . $e->getMessage());
        }

        // 5. Mesej Balas (Feedback)
        if ($service->approval_status == 'suspended') {
            return back()->with('error', 'Amaran ke-3! Servis ini telah digantung (suspended).');
        }

        return back()->with('success', 'Warning berjaya dihantar. Jumlah warning: ' . $service->warning_count);
    }
}
