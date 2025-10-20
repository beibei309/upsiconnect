<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    public function index(): JsonResponse
    {
        $pending = User::query()
            ->where('role', 'community')
            ->where('verification_status', 'pending')
            ->get(['id', 'name', 'email', 'phone', 'profile_photo_path', 'selfie_media_path']);

        return response()->json(['pending' => $pending]);
    }

    public function approve(User $user): JsonResponse
    {
        $user->update([
            'verification_status' => 'approved',
            'public_verified_at' => now(),
        ]);

        return response()->json(['user' => $user]);
    }

    public function reject(User $user): JsonResponse
    {
        $user->update([
            'verification_status' => 'rejected',
        ]);

        return response()->json(['user' => $user]);
    }
}