<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    /**
     * Get all favorites for the authenticated user
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['services' => function($query) {
                $query->where('is_active', true);
            }])
            ->withCount('services')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add a user to favorites
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $user = Auth::user();
        
        // Check if already favorited
        if ($user->favorites()->where('favorited_user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already in your favorites'
            ], 422);
        }

        // Add to favorites
        $user->favorites()->attach($request->user_id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User added to favorites successfully'
        ]);
    }

    /**
     * Remove a user from favorites
     */
    public function destroy(User $user): JsonResponse
    {
        $authUser = Auth::user();
        
        // Check if user is in favorites
        if (!$authUser->favorites()->where('favorited_user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not in your favorites'
            ], 404);
        }

        // Remove from favorites
        $authUser->favorites()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'User removed from favorites successfully'
        ]);
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $user = Auth::user();
        $favoritedUserId = $request->user_id;

        // Check if already favorited
        $isFavorited = $user->favorites()->where('favorited_user_id', $favoritedUserId)->exists();

        if ($isFavorited) {
            // Remove from favorites
            $user->favorites()->detach($favoritedUserId);
            $message = 'User removed from favorites';
            $favorited = false;
        } else {
            // Add to favorites
            $user->favorites()->attach($favoritedUserId, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'User added to favorites';
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'favorited' => $favorited
        ]);
    }

    /**
     * Check if a user is favorited
     */
    public function check(User $user): JsonResponse
    {
        $isFavorited = Auth::user()->favorites()
            ->where('favorited_user_id', $user->id)
            ->exists();

        return response()->json([
            'favorited' => $isFavorited
        ]);
    }
}
