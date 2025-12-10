<?php

namespace App\Http\Controllers;

use App\Models\StudentService;
use App\Models\User;
use App\Models\Category;
use App\Models\ServiceRequest;
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
        ->where('status', 'available')
        ->where('approval_status', 'approved') // <--- Added this line
        ->whereHas('student', function ($q) {
            $q->where('role', 'helper');
        });

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
        // Note: Ensure 'basic_price' is the column you want to sort by
        $query->orderBy('basic_price', 'asc'); 
    } elseif ($sort == 'price_high') {
        $query->orderBy('basic_price', 'desc');
    }

    // Fetch the services
    $services = $query->get();

    return view('services.index', [
        'services' => $services,
        'categories' => \App\Models\Category::all(), // Ensure Model is imported or fully qualified
        'category_id' => $category_id,
        'sort' => $sort,
    ]);
}

    public function edit(StudentService $service)
    {
        $user = auth()->user();
        if (!$user || $user->id !== $service->user_id) {
            abort(403, 'You may only edit your own services.');
        }

        // Get categories for dropdown
        $categories = \App\Models\Category::all();

        return view('services.edit', compact('service', 'categories'));
    }


   public function update(Request $request, StudentService $service): JsonResponse
{
    $user = $request->user();
    if (!$user || $user->id !== $service->user_id) {
        return response()->json(['error' => 'You may only update your own services.'], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048',
        'description' => 'nullable|string',
        'packages' => 'nullable|array',
        'packages.*.duration' => 'nullable|string',
        'packages.*.frequency' => 'nullable|string',
        'packages.*.price' => 'nullable|numeric|min:0',
        'packages.*.description' => 'nullable|string',
        'offer_packages' => 'nullable', // checkbox toggle
        'unavailable_dates' => 'nullable|string',
    ]);

    // Image
    if ($request->hasFile('image')) {
        $service->image_path = $request->file('image')->store('services','public');
    }

    $service->title = $validated['title'];
    $service->category_id = $validated['category_id'];
    $service->description = $validated['description'] ?? '';

    // Packages
    $packages = $request->input('packages', []);

    // Always save Basic package
    $service->basic_duration = $packages[0]['duration'] ?? null;
    $service->basic_frequency = $packages[0]['frequency'] ?? null;
    $service->basic_price = $packages[0]['price'] ?? null;
    $service->basic_description = $packages[0]['description'] ?? null;

    // Check if student wants to offer Standard/Premium
    if ($request->has('offer_packages')) {
        // Save Standard
        $service->standard_duration = $packages[1]['duration'] ?? null;
        $service->standard_frequency = $packages[1]['frequency'] ?? null;
        $service->standard_price = $packages[1]['price'] ?? null;
        $service->standard_description = $packages[1]['description'] ?? null;

        // Save Premium
        $service->premium_duration = $packages[2]['duration'] ?? null;
        $service->premium_frequency = $packages[2]['frequency'] ?? null;
        $service->premium_price = $packages[2]['price'] ?? null;
        $service->premium_description = $packages[2]['description'] ?? null;
    } else {
        // Clear Standard & Premium if toggle is off
        $service->standard_duration = null;
        $service->standard_frequency = null;
        $service->standard_price = null;
        $service->standard_description = null;

        $service->premium_duration = null;
        $service->premium_frequency = null;
        $service->premium_price = null;
        $service->premium_description = null;
    }

    // Unavailable Dates
    $dates = $request->input('unavailable_dates', '');
    $service->unavailable_dates = $dates ? json_encode(array_filter(explode(',', $dates))) : '[]';

    $service->save();

    return response()->json([
        'success' => true,
        'message' => 'Service updated successfully!',
        'service' => $service
    ]);
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

        // Cari perkhidmatan sedia ada atau sediakan objek baru
        if ($serviceId) {
            $service = StudentService::findOrFail($serviceId);
            if ($service->user_id !== $user->id) {
                return response()->json(['error' => 'You may only edit your own services.'], 403);
            }
        } else {
            // Jika ini rekod baharu, pastikan kita berada di langkah 'overview'
            if ($currentSection !== 'overview') {
                return response()->json(['error' => 'New services must start with the overview section.'], 400);
            }
            $service = new StudentService();
            $service->user_id = $user->id;
            $service->approval_status = 'pending';
            // Set nilai lalai untuk mengelakkan ralat MySQL jika medan lain tidak boleh null
            $service->status = 'available'; // Contoh, pastikan status ada nilai
            $service->is_active = true;     // Contoh
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
            // ... (kod validasi pricing anda kekal sama) ...
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
        } elseif ($currentSection === 'availability') {
            $rules = [
                'unavailable_dates' => 'nullable|string'
            ];
        }
       
        $validated = $request->validate($rules);

        // --- HANDLE DATA ASSIGNMENT ---

        if ($currentSection === 'overview') {
            // Ini akan menyelesaikan ralat: title kini diberikan nilai
            $service->title = $validated['title']; 
            $service->category_id = $validated['category_id'];
            
            if ($request->hasFile('image')) {
                $service->image_path = $request->file('image')->store('services', 'public');
            } elseif ($request->filled('template_image')) {
                $service->image_path = $request->input('template_image');
            }
        }

        if ($currentSection === 'description') {
            $service->description = $validated['description'];
        }

        if ($currentSection === 'availability') {
            $dates = $request->input('unavailable_dates', []);
            if (is_string($dates)) {
                $dates = array_filter(explode(',', $dates));
            }
            $service->unavailable_dates = json_encode(array_values($dates));
        }

        if ($currentSection === 'pricing' && $request->filled('packages')) {
            $packages = $request->input('packages');
            
            // ... (logik pricing anda kekal sama) ...
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

        // Simpan ke pangkalan data
        // Pada langkah 'overview', 'title' kini sudah diset, jadi tiada ralat.
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
        $service = StudentService::with(['user', 'category', 'orders'])->findOrFail($id);
        $viewer = $request->user(); // currently logged-in user (if any)

        // Fetch orders for this service
        $orders = ServiceRequest::where('student_service_id', $service->id)
                    ->whereIn('status', ['completed', 'accepted'])
                    ->get();

        $service->min_price = $orders->min('offered_price') ?? 0;
        $service->max_price = $orders->max('offered_price') ?? 0;

        // Completed orders count
    $service->completed_orders = $service->orders()
        ->whereIn('status', ['completed', 'accepted'])
        ->count();

    // Average rating from reviews received by the user
    $service->rating = round($service->user->reviewsReceived()->avg('rating'), 1) ?? 0;

        // Optional: calculate average delivery time in days
        $service->avg_days = $orders->avg(function($order) {
            return \Carbon\Carbon::parse($order->selected_dates)->diffInDays(now());
        }) ?? 0;

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