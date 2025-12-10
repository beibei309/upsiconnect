<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentService;
use App\Models\Category;

use Illuminate\Http\Request;

class AdminServicesController extends Controller
{
    // Display the list of services with search and filtering
    public function index(Request $request)
    {
        // Get the search query
        $search = $request->query('search');

        // Fetch services with optional search query
        $services = StudentService::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%$search%")
                             ->orWhere('description', 'like', "%$search%");
            })
            ->with('user', 'category')
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    // Approve a service
    public function approve(StudentService $service)
    {
        $service->approval_status = 'approved';
        $service->save();

        return redirect()->route('admin.services.index')->with('success', 'Service approved.');
    }

    // Reject a service
    public function reject(StudentService $service)
    {
        $service->approval_status = 'rejected';
        $service->save();

        return redirect()->route('admin.services.index')->with('success', 'Service rejected.');
    }

    // Delete/Destroy service record
    public function destroy(StudentService $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service has been permanently deleted.');
    }
}
