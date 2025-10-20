<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Authentication required.'], 401);
        }

        $conversation = Conversation::findOrFail($data['conversation_id']);

        // Ensure user is a participant
        if (!in_array($user->id, [$conversation->student_id, $conversation->customer_id])) {
            return response()->json(['error' => 'You are not a participant in this conversation.'], 403);
        }

        // Prevent duplicate review from same reviewer to same reviewee in the conversation
        $revieweeId = $user->id === $conversation->student_id ? $conversation->customer_id : $conversation->student_id;
        $exists = Review::where('conversation_id', $conversation->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewee_id', $revieweeId)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'You have already reviewed this user for this conversation.'], 422);
        }

        $review = Review::create([
            'conversation_id' => $conversation->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $revieweeId,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return response()->json(['review' => $review], 201);
    }
}