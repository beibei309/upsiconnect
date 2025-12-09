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
        $query->orderBy('basic_price', 'desc');
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
            'basic_price' => ['nullable', 'numeric', 'min:0'],
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
        if ($user->role !== 'helper') {
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
        if (!$user || $user->role !== 'helper') {
            abort(403, 'Only students can create services.');
        }

        // Get categories for the form
        $categories = \App\Models\Category::all();

        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'helper') {
            abort(403, 'Only helpers can create services.');
        }

        $currentSection = $request->input('current_section');
        $serviceId = $request->input('service_id');

        // Find or create service instance
        if ($serviceId) {
            $service = StudentService::findOrFail($serviceId);
            if ($service->user_id !== $user->id) {
                return response()->json(['error' => 'You may only edit your own services.'], 403);
            }
        } else {
            $service = new StudentService();
            $service->user_id = $user->id;
            $service->approval_status = 'pending';
        }

        // --- VALIDATION PER SECTION ---
        $rules = [];
        if ($currentSection === 'overview') {
            $rules = [
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|max:2048',
                'template_image' => 'nullable|string',
            ];
        } elseif ($currentSection === 'pricing') {
            $rules = [
                'packages.0.duration' => 'required|string',
                'packages.0.frequency' => 'required|string',
                'packages.0.price' => 'required|numeric|min:0',
                'packages.0.description' => 'nullable|string',
            ];

            if ($request->has('offer_packages')) {
                $rules['packages.1.duration'] = 'required|string';
                $rules['packages.1.frequency'] = 'required|string';
                $rules['packages.1.price'] = 'required|numeric|min:0';
                $rules['packages.1.description'] = 'nullable|string';

                $rules['packages.2.duration'] = 'required|string';
                $rules['packages.2.frequency'] = 'required|string';
                $rules['packages.2.price'] = 'required|numeric|min:0';
                $rules['packages.2.description'] = 'nullable|string';
            }
        } elseif ($currentSection === 'description') {
            $rules = [
                'description' => 'required|string',
            ];
        }

        $validated = $request->validate($rules);

        // --- HANDLE IMAGE IN OVERVIEW ---
        if ($currentSection === 'overview') {
            if ($request->hasFile('image')) {
                $validated['image_path'] = $request->file('image')->store('services', 'public');
            } elseif ($request->filled('template_image')) {
                $validated['image_path'] = $request->input('template_image');
            }

            $service->title = $validated['title'];
            $service->category_id = $validated['category_id'];
            if (isset($validated['image_path'])) $service->image_path = $validated['image_path'];
        }

        // --- DESCRIPTION ---
        if ($currentSection === 'description') {
            $service->description = $validated['description'];
        }

        // --- PRICING ---
        if ($currentSection === 'pricing' && $request->filled('packages')) {
            $packages = $request->input('packages');

            // Handle pricing logic for basic, standard, and premium packages
            $service->basic_duration = $packages[0]['duration'] ?? null;
            $service->basic_frequency = $packages[0]['frequency'] ?? null;
            $service->basic_price = $packages[0]['price'] ?? null;
            $service->basic_description = $packages[0]['description'] ?? null;

            if (!empty($packages[1])) {
                $service->standard_duration = $packages[1]['duration'] ?? null;
                $service->standard_frequency = $packages[1]['frequency'] ?? null;
                $service->standard_price = $packages[1]['price'] ?? null;
                $service->standard_description = $packages[1]['description'] ?? null;
            }

            if (!empty($packages[2])) {
                $service->premium_duration = $packages[2]['duration'] ?? null;
                $service->premium_frequency = $packages[2]['frequency'] ?? null;
                $service->premium_price = $packages[2]['price'] ?? null;
                $service->premium_description = $packages[2]['description'] ?? null;
            }
        }

        $service->save();

        return response()->json([
            'success' => true,
            'service' => $service
        ]);
    }

    public function manage(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'helper') {
            abort(403, 'Only students helper can manage services.');
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