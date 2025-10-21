<?php

namespace App\Http\Controllers;

use App\Models\StudentService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentServiceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'Only authenticated students can create services.'], 403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'suggested_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $service = StudentService::create([
            'user_id' => $user->id,
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'suggested_price' => $data['suggested_price'] ?? null,
            'is_active' => true,
        ]);

        return response()->json(['service' => $service], 201);
    }

    public function update(Request $request, StudentService $service): JsonResponse
    {
        $user = $request->user();
        if (!$user || $user->id !== $service->user_id) {
            return response()->json(['error' => 'You may only update your own services.'], 403);
        }

        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'suggested_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service->update($data);

        return response()->json(['service' => $service], 200);
    }

    public function destroy(Request $request, StudentService $service): JsonResponse
    {
        $user = $request->user();
        if (!$user || $user->id !== $service->user_id) {
            return response()->json(['error' => 'You may only delete your own services.'], 403);
        }

        // Prefer soft disable to keep history
        $service->update(['is_active' => false]);

        return response()->json(['service' => $service], 200);
    }

    public function storefront(User $user): JsonResponse
    {
        if ($user->role !== 'student') {
            return response()->json(['error' => 'User is not a student.'], 422);
        }

        $services = StudentService::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->with('category')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'badge' => $user->trust_badge,
                'is_available' => $user->is_available,
                'average_rating' => $user->average_rating,
            ],
            'services' => $services,
        ], 200);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'student') {
            abort(403, 'Only students can create services.');
        }

        // Get categories for the form
        $categories = \App\Models\Category::all();

        return view('services.create', compact('categories'));
    }

    public function manage(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'student') {
            abort(403, 'Only students can manage services.');
        }

        $services = StudentService::query()
            ->where('user_id', $user->id)
            ->with('category')
            ->orderByDesc('created_at')
            ->get();

        return view('services.manage', compact('services'));
    }
}