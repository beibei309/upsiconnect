<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Conversation;
use App\Models\ServiceRequest;
use App\Models\ServiceApplication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'service_application_id' => 'nullable|exists:service_applications,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Ensure either conversation_id, service_request_id, or service_application_id is provided
        if (!$request->conversation_id && !$request->service_request_id && !$request->service_application_id) {
            return response()->json(['error' => 'Either conversation_id, service_request_id, or service_application_id is required'], 400);
        }

        $revieweeId = null;

        if ($request->conversation_id) {
            // Existing conversation-based review logic
            $conversation = Conversation::findOrFail($request->conversation_id);
            
            // Determine who is being reviewed
            if ($conversation->student_id == auth()->id()) {
                $revieweeId = $conversation->community_member_id;
            } else {
                $revieweeId = $conversation->student_id;
            }
        } elseif ($request->service_request_id) {
            // New service request-based review logic
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);
            
            // Ensure the user is part of this service request
            if ($serviceRequest->requester_id != auth()->id() && $serviceRequest->provider_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Ensure the service request is completed
            if (!$serviceRequest->isCompleted()) {
                return response()->json(['error' => 'Service request must be completed before reviewing'], 400);
            }
            
            // Determine who is being reviewed
            if ($serviceRequest->requester_id == auth()->id()) {
                $revieweeId = $serviceRequest->provider_id;
            } else {
                $revieweeId = $serviceRequest->requester_id;
            }
        } elseif ($request->service_application_id) {
            // New service application-based review logic
            $serviceApplication = ServiceApplication::findOrFail($request->service_application_id);
            
            // Ensure the user is part of this service application
            if ($serviceApplication->customer_id != auth()->id() && $serviceApplication->service->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Ensure the service application is fully completed
            if (!$serviceApplication->isFullyCompleted()) {
                return response()->json(['error' => 'Service application must be fully completed before reviewing'], 400);
            }
            
            // Determine who is being reviewed
            if ($serviceApplication->customer_id == auth()->id()) {
                $revieweeId = $serviceApplication->service->user_id; // Provider
            } else {
                $revieweeId = $serviceApplication->customer_id; // Customer
            }
        }

        // Check if user has already reviewed this conversation/service request/service application
        $existingReview = Review::where('reviewer_id', auth()->id())
            ->where(function ($query) use ($request) {
                if ($request->conversation_id) {
                    $query->where('conversation_id', $request->conversation_id);
                } elseif ($request->service_request_id) {
                    $query->where('service_request_id', $request->service_request_id);
                } else {
                    $query->where('service_application_id', $request->service_application_id);
                }
            })
            ->first();

        if ($existingReview) {
            return response()->json(['error' => 'You have already reviewed this'], 400);
        }

        $review = Review::create([
            'conversation_id' => $request->conversation_id,
            'service_request_id' => $request->service_request_id,
            'service_application_id' => $request->service_application_id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'review' => $review
        ]);
    }
}