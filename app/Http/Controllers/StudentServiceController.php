<?php

namespace App\Http\Controllers;

use App\Models\StudentService;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentServiceController extends Controller
{

  public function index(Request $request)
{
    $q = $request->string('q')->toString();
    $category_id = $request->category_id;
    $sort = $request->sort ?? 'newest';

    $query = StudentService::with(['student', 'category'])
        ->where('status', 'available'); 

    // Search filter
    if ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%");
        });
    }

    // Category filter
    if ($category_id) {
        $query->where('category_id', $category_id);
    }

    // Sorting
    if ($sort == 'newest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($sort == 'oldest') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort == 'price_low') {
        $query->orderBy('suggested_price', 'asc');
    } elseif ($sort == 'price_high') {
        $query->orderBy('suggested_price', 'desc');
    }

    // Fetch the services
    $services = $query->get();

    // Return to the view (similar to what was done in GuestServicesController)
    return view('services.index', [
        'services' => $services,
        'categories' => Category::all(),
        'category_id' => $category_id,
        'sort' => $sort,
    ]);
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

        // Hard delete
        $service->delete();

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

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'student') {
            abort(403, 'Only students can create services.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'suggested_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048', // optional upload
            'template_image' => 'nullable|string', // optional template
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image_path'] = $path;
        } elseif ($request->filled('template_image')) {
            $data['image_path'] = $request->template_image;
        }

        $data['user_id'] = $user->id;
        $data['approval_status'] = 'pending'; // Set the status to 'pending' by default

        $service = StudentService::create($data);

        return response()->json(['success' => true, 'service' => $service]);
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

    public function show(Request $request, StudentService $service)
    {
        // Ensure the service is active/available for viewing
        if (!$service->is_active) {
            abort(404);
        }

        $service->load(['user', 'category']);

        $viewer = $request->user();

        return view('services.show', [
            'service' => $service,
            'provider' => $service->user,
            'viewer' => $viewer,
        ]);
    }

    public function details(Request $request, $id)
    {
        $service = StudentService::with(['user', 'category'])->findOrFail($id);

        $viewer = $request->user(); // currently logged-in user (if any)

        return view('services.details', [
            'service' => $service,
            'provider' => $service->user,
            'viewer' => $viewer,
        ]);
    }

    // ADMIN APPROVE/REJECT SERVICE
    public function approve(StudentService $service)
    {
        // Ensure the user is an admin
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'You are not authorized to approve services.');
        }

        $service->approval_status = 'approved';
        $service->save();

        return response()->json(['success' => 'Service approved.']);
    }

    public function reject(StudentService $service)
    {
        // Ensure the user is an admin
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'You are not authorized to reject services.');
        }

        $service->approval_status = 'rejected';
        $service->save();

        return response()->json(['success' => 'Service rejected.']);
    }



    


}