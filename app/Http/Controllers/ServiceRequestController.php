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
    try {
        $user = Auth::user();
        if (!$user->isCommunity()) {
            return response()->json(['error' => 'Only community members can request services.'], 403);
        }

        $validated = $request->validate([
            'student_service_id' => 'required|exists:student_services,id',
            'selected_dates' => 'required|string',
            'selected_package' => 'required|string',
            'message' => 'nullable|string|max:1000',
            'offered_price' => 'nullable|numeric|min:0|max:99999.99'
        ]);

        $studentService = StudentService::findOrFail($validated['student_service_id']);

        if (!$studentService->is_active) {
            return response()->json(['error' => 'This service is no longer available.'], 400);
        }

        if (!$studentService->user->is_available) {
            return response()->json(['error' => 'This service provider is currently unavailable.'], 400);
        }

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
            'selected_dates' => $validated['selected_dates'],
            'selected_package' => $validated['selected_package'],
            'message' => $validated['message'],
            'offered_price' => $validated['offered_price'],
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service request sent successfully!',
            'request_id' => $serviceRequest->id
        ]);
    } catch (\Exception $e) {
        \Log::error('ServiceRequest store error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Show service requests for the authenticated user
     */
   public function index(Request $request)
{
    $user = Auth::user();

    // 1. Jika User adalah HELPER
    if ($user->role === 'helper') {
        // Ambil request yang DITERIMA oleh helper ini
        $receivedRequests = \App\Models\ServiceRequest::where('provider_id', $user->id)
            ->with(['requester', 'studentService']) // Eager loading
            ->orderBy('created_at', 'desc')
            ->get();

        // Hantar ke fail 'service-requests.helper'
        return view('service-requests.helper', compact('receivedRequests'));
    }

    // 2. Jika User adalah BIASA (Student/User)
    else {
        // Ambil request yang DIHANTAR oleh user ini
        $sentRequests = \App\Models\ServiceRequest::where('requester_id', $user->id)
            ->with(['provider', 'studentService']) // Eager loading
            ->orderBy('created_at', 'desc')
            ->get();

        // Hantar ke fail 'service-requests.index'
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
}