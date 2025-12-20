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

 public function sendWarning(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $service = StudentService::with('user')->findOrFail($id);

        // Update Database
        $service->increment('warning_count');
        $service->warning_reason = $request->input('reason');
        
        $statusType = 'warning'; // Default notification type

        // Logic auto-block if count >= 3
        if($service->warning_count >= 3) {
            $service->approval_status = 'blocked';
            $statusType = 'blocked'; // Change notification type if blocked
        }

        $service->save();

        // 1. Send Email
        if ($service->user && $service->user->email) {
            Mail::to($service->user->email)
                ->send(new ServiceWarningMail($service, $request->input('reason')));
        }

        // 2. Send Database Notification
        if ($service->user) {
            $service->user->notify(new ServiceStatusNotification($statusType, $service, $request->input('reason')));
        }

        return back()->with('success', 'Warning sent, email dispatched, and notification created.');
    }
    
}
