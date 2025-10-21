<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);
        $user = Auth::user();

        // Check if user is part of this conversation
        if (!in_array($user->id, [$conversation->student_id, $conversation->customer_id])) {
            return response()->json(['error' => 'You are not authorized to send messages in this conversation.'], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'content' => $data['message'],
            'body' => $data['message'], // For backward compatibility
        ]);

        // Update conversation timestamp
        $conversation->touch();

        return response()->json([
            'message' => $message->load('sender'),
            'success' => true
        ], 201);
    }
}