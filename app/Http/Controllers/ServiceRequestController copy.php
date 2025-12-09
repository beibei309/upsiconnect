<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        
        // Only community members (including staff) can request services
        if (!$user->isCommunity()) {
            return response()->json(['error' => 'Only community members can request services.'], 403);
        }

        // Check if user is verified (staff are automatically verified)
        if (!$user->isVerifiedPublic() && !$user->isVerifiedStaff()) {
            return response()->json(['error' => 'Please complete your account verification first.'], 403);
        }

        $validated = $request->validate([
            'student_service_id' => 'required|exists:student_services,id',
            'message' => 'nullable|string|max:1000',
            'offered_price' => 'nullable|numeric|min:0|max:99999.99'
        ]);

        $studentService = StudentService::findOrFail($validated['student_service_id']);
        
        // Check if service is active
        if (!$studentService->is_active) {
            return response()->json(['error' => 'This service is no longer available.'], 400);
        }

        // Check if provider is available
        if (!$studentService->user->is_available) {
            return response()->json(['error' => 'This service provider is currently unavailable.'], 400);
        }

        // Check if user already has a pending request for this service
        $existingRequest = ServiceRequest::where('student_service_id', $studentService->id)
            ->where('requester_id', $user->id)
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->first();

        if ($existingRequest) {
            return response()->json(['error' => 'You already have an active request for this service.'], 400);
        }

        $serviceRequest = ServiceRequest::create([
            'student_service_id' => $studentService->id,
            'requester_id' => $user->id,
            'provider_id' => $studentService->user_id,
            'message' => $validated['message'],
            'offered_price' => $validated['offered_price'],
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request sent successfully! The provider will be notified.',
            'request_id' => $serviceRequest->id
        ]);
    }

    /**
     * Show service requests for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        // Normalize status filter (supports hyphen or underscore) and validate
        $status = str_replace('-', '_', strtolower($status));
        $validStatuses = ['all', 'pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            $status = 'all';
        }

        // Build both lists so the view can render tabs reliably
        $sentQuery = ServiceRequest::where('requester_id', $user->id)
            ->with(['studentService', 'provider']);
        $receivedQuery = ServiceRequest::where('provider_id', $user->id)
            ->with(['studentService', 'requester']);

        if ($status !== 'all') {
            $sentQuery->where('status', $status);
            $receivedQuery->where('status', $status);
        }

        $sentRequests = $sentQuery->orderBy('created_at', 'desc')->get();
        $receivedRequests = $receivedQuery->orderBy('created_at', 'desc')->get();

        return view('service-requests.index', compact('sentRequests', 'receivedRequests', 'status'));
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
}