<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\StudentService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;

class ServiceRequestController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new service request
     */
 public function store(Request $request)
{
    try {
        $user = Auth::user();
        
        // 1. Validate basic fields (Make times nullable for flexibility)
        $validated = $request->validate([
            'student_service_id' => 'required|exists:student_services,id',
            'selected_dates'     => 'required|date',
            'start_time'         => 'nullable|string', 
            'end_time'           => 'nullable|string',
            'selected_package'   => 'required|string',
            'message'            => 'nullable|string|max:1000',
            'offered_price'      => 'nullable|numeric|min:0|max:99999.99'
        ]);

        $studentService = StudentService::findOrFail($validated['student_service_id']);

        // Check availability
        if (!$studentService->is_active || !$studentService->user->is_available) {
            return response()->json(['error' => 'Service or provider unavailable.'], 400);
        }

        // Check for existing active requests from this user
        $hasActiveRequest = ServiceRequest::where('requester_id', $user->id)
            ->where('provider_id', $studentService->user_id)
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->exists();

        if ($hasActiveRequest) {
            return response()->json(['error' => 'You already have an active request with this helper.'], 400);
        }

        // --- LOGIC SPLIT: Session vs Task ---
        $startTime = $validated['start_time'];
        $endTime   = $validated['end_time'];

        // If it is Session Based, we MUST have times and check overlap
        if ($studentService->session_duration) {
            if (!$startTime || !$endTime) {
                return response()->json(['error' => 'Start and End time are required for this service.'], 422);
            }

            // Check Overlap logic ONLY for session-based services
            $overlapping = ServiceRequest::where('student_service_id', $studentService->id)
                ->where('selected_dates', $validated['selected_dates'])
                ->whereIn('status', ['pending', 'accepted', 'in_progress', 'approved'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                })
                ->exists();

            if ($overlapping) {
                return response()->json(['error' => 'This time slot is booked. Please select another.'], 400);
            }
        } else {
            // Task Based (No session duration)
            // Default to full day if not provided by frontend
            $startTime = $startTime ?? '00:00';
            $endTime   = $endTime ?? '23:59';
            
            // Optional: Check if provider already has a task for this specific DATE?
            // If you want to allow multiple tasks per day, do nothing here.
        }

        // Create Request
        $serviceRequest = ServiceRequest::create([
            'student_service_id' => $studentService->id,
            'requester_id'       => $user->id,
            'provider_id'        => $studentService->user_id,
            'selected_dates'     => $validated['selected_dates'],
            'start_time'         => $startTime,
            'end_time'           => $endTime,
            'selected_package'   => json_encode($validated['selected_package']),
            'message'            => $validated['message'],
            'offered_price'      => $validated['offered_price'],
            'status'             => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request sent successfully!',
            'request_id' => $serviceRequest->id
        ]);

    } catch (\Exception $e) {
        \Log::error('ServiceRequest store error: ' . $e->getMessage());
        return response()->json(['error' => 'Server error occurred.'], 500);
    }
}

public function index(Request $request)
{
    $user = Auth::user();
    
    // Determine the mode. If not set, default based on role logic or 'buyer'
    $viewMode = session('view_mode', 'buyer');

    // 1. HELPER MODE (Seller View)
    // Only show if user is actually a helper AND is in 'seller' mode
    if ($user->role === 'helper' && $viewMode === 'seller') {
        
        $receivedRequests = \App\Models\ServiceRequest::where('provider_id', $user->id)
            ->with(['requester', 'studentService'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('service-requests.helper', compact('receivedRequests'));
    }

    // 2. BUYER MODE (Student View)
    // Default for 'student' role OR 'helper' role in 'buyer' mode
    else {
        
        $sentRequests = \App\Models\ServiceRequest::where('requester_id', $user->id)
            ->with(['provider', 'studentService'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('service-requests.index', compact('sentRequests'));
    }
}

    /**
     * Show a specific service request
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Only requester and provider can view the request
        if ($serviceRequest->requester_id !== $user->id && $serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to view this request.');
        }

        $serviceRequest->load([
            'studentService.category', 
            'requester', 
            'provider',
            'reviews.reviewer'
        ]);

        return view('service-requests.show', compact('serviceRequest'));
    }

    /**
     * Accept a service request
     */
    public function accept(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Only the provider can accept
        if ($serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to accept this request.');
        }

        if (!$serviceRequest->isPending()) {
            return response()->json(['error' => 'This request cannot be accepted.'], 400);
        }

        $serviceRequest->accept();

        return response()->json([
            'success' => true,
            'message' => 'Service request accepted successfully!'
        ]);
    }

    /**
     * Reject a service request
     */
    public function reject(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Only the provider can reject
        if ($serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to reject this request.');
        }

        if (!$serviceRequest->isPending()) {
            return response()->json(['error' => 'This request cannot be rejected.'], 400);
        }

        $serviceRequest->reject();

        return response()->json([
            'success' => true,
            'message' => 'Service request rejected.'
        ]);
    }

    /**
     * Mark service request as in progress
     */
    public function markInProgress(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Only the provider can mark as in progress
        if ($serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to update this request.');
        }

        if (!$serviceRequest->isAccepted()) {
            return response()->json(['error' => 'This request must be accepted first.'], 400);
        }

        $serviceRequest->markInProgress();

        return response()->json([
            'success' => true,
            'message' => 'Service marked as in progress!'
        ]);
    }

    /**
     * Mark service request as completed
     */
    public function markCompleted(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Only the provider can mark as completed
        if ($serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to update this request.');
        }

        if (!$serviceRequest->isInProgress()) {
            return response()->json(['error' => 'This request must be in progress first.'], 400);
        }

        $serviceRequest->markCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Service marked as completed! Both parties can now leave reviews.'
        ]);
    }

    /**
     * Cancel a service request
     */
    public function cancel(ServiceRequest $serviceRequest)
    {
        $user = Auth::user();
        
        // Both requester and provider can cancel
        if ($serviceRequest->requester_id !== $user->id && $serviceRequest->provider_id !== $user->id) {
            abort(403, 'You are not authorized to cancel this request.');
        }

        if ($serviceRequest->isCompleted()) {
            return response()->json(['error' => 'Completed requests cannot be cancelled.'], 400);
        }

        $serviceRequest->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Service request cancelled.'
        ]);
    }

    public function updateStatus(Request $request, $id)
{
    $serviceRequest = ServiceRequest::findOrFail($id);
    
    // Validate that the user owns the request or is the provider
    if (auth()->id() !== $serviceRequest->requester_id && auth()->id() !== $serviceRequest->provider_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Validate the new status
    $validated = $request->validate([
        'status' => 'required|in:pending,accepted,in_progress,completed,cancelled,rejected'
    ]);

    // Update the status
    $serviceRequest->update([
        'status' => $validated['status']
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Booking status updated to ' . $validated['status']
    ]);
}
}