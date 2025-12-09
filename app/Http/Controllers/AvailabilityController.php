<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function toggle(): JsonResponse
    {
        $user = request()->user();
        if (!$user || $user->role !== 'helper') {
            return response()->json(['error' => 'Only authenticated helpers may toggle availability.'], 403);
        }

        $user->is_available = !$user->is_available;

        // kalau jadi unavailable, kosongkan dulu tarikh supaya frontend set baru
        if (!$user->is_available) {
            $user->unavailable_start_date = null;
            $user->unavailable_end_date = null;
        }

        $user->save();

        return response()->json([
            'is_available' => $user->is_available,
            'message' => $user->is_available ? 'Saya Bersedia' : 'Sila pilih tarikh tidak available',
        ]);
    }

    public function setDates(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user->unavailable_start_date = $request->start_date;
        $user->unavailable_end_date = $request->end_date;
        $user->is_available = false;
        $user->save();

        return response()->json([
            'message' => 'Tarikh unavailable telah disimpan.',
            'is_available' => $user->is_available,
            'start_date' => $user->unavailable_start_date,
            'end_date' => $user->unavailable_end_date,
        ]);
    }
}
