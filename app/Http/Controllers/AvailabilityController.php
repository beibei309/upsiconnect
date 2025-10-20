<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function toggle(): JsonResponse
    {
        $user = request()->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'Only authenticated students may toggle availability.'], 403);
        }

        $user->is_available = !$user->is_available;
        $user->save();

        return response()->json([
            'is_available' => $user->is_available,
            'message' => $user->is_available ? 'Saya Bersedia' : 'Saya Sedang Bercuti',
        ], 200);
    }
}