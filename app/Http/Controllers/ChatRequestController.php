<?php

namespace App\Http\Controllers;

use App\Models\ChatRequest;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ChatRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string'],
        ]);

        $student = User::findOrFail($data['student_id']);

        if ($student->role !== 'student') {
            return response()->json(['error' => 'Recipient must be a verified student.'], 422);
        }

        if (!$student->isAvailable()) {
            return response()->json(['error' => 'Student currently unavailable.'], 422);
        }

        $requester = $request->user();
        if (!$requester) {
            return response()->json(['error' => 'Authentication required.'], 401);
        }

        if (!($requester->isVerifiedPublic() || $requester->isVerifiedStaff())) {
            return response()->json(['error' => 'Only verified users may request to chat.'], 403);
        }

        $chatRequest = ChatRequest::create([
            'requester_id' => $requester->id,
            'recipient_id' => $student->id,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['chat_request' => $chatRequest], 201);
    }

    public function accept(ChatRequest $chatRequest): JsonResponse
    {
        $student = request()->user();
        if (!$student || $student->id !== $chatRequest->recipient_id) {
            return response()->json(['error' => 'Only the recipient student may accept.'], 403);
        }

        if ($chatRequest->status !== 'pending') {
            return response()->json(['error' => 'Chat request is not pending.'], 422);
        }

        $chatRequest->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $conversation = Conversation::create([
            'chat_request_id' => $chatRequest->id,
            'student_id' => $chatRequest->recipient_id,
            'customer_id' => $chatRequest->requester_id,
            'started_at' => now(),
        ]);

        return response()->json(['conversation' => $conversation], 200);
    }

    public function decline(ChatRequest $chatRequest): JsonResponse
    {
        $student = request()->user();
        if (!$student || $student->id !== $chatRequest->recipient_id) {
            return response()->json(['error' => 'Only the recipient student may decline.'], 403);
        }

        if ($chatRequest->status !== 'pending') {
            return response()->json(['error' => 'Chat request is not pending.'], 422);
        }

        $chatRequest->update([
            'status' => 'declined',
            'declined_at' => now(),
        ]);

        return response()->json(['chat_request' => $chatRequest], 200);
    }
}