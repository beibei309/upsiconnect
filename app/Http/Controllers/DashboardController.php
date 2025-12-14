<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Category;
use App\Models\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

public function index(Request $request)
{
    // --- 1. Get Inputs ---
    $q = $request->query('q'); 
    $category_id = $request->query('category_id'); 
    $min_rating = $request->query('min_rating');
    $available = $request->query('available');

    $currentUserId = Auth::id(); 
    
    // --- 2. Initialize Base Query & Eager Loads ---
    $query = StudentService::with([
        'category',
        'user' => function ($q) {
            $q->withCount('reviewsReceived')
              ->withAvg('reviewsReceived as average_rating', 'rating');
        }
    ])
    ->where('is_active', true)
    ->where('approval_status', 'approved');

    // --- 3. Apply Filters to the Query Builder ---
    
    // FIX: Exclude the current user's own services
    if ($currentUserId) {
        $query->where('user_id', '!=', $currentUserId);
    }
    
    // NOTE: You would add other filters ($q, $category_id, $min_rating, $available) here.
    
    // --- 4. Execute the Query ---
    $services = $query->latest()->get();

    // --- 5. Get Categories with Count of APPROVED services (No changes needed here) ---
    $categories = Category::withCount(['services' => function ($q) use ($currentUserId) {
        $q->where('is_active', true)
          ->where('approval_status', 'approved');
          // Optional: Exclude user's own services from the category count as well
          if ($currentUserId) {
              $q->where('user_id', '!=', $currentUserId);
          }
    }])->get();

    // --- 6. Get Top Providers (No changes needed here) ---
    $topStudents = User::where('role', 'helper')
    // ... (rest of the topStudents query remains the same)
    ->whereHas('services', function ($q) {
        $q->where('is_active', true)
          ->where('approval_status', 'approved');
    })
    ->withCount([
        'services' => function ($q) {
            $q->where('is_active', true)
              ->where('approval_status', 'approved');
        },
        'reviewsReceived'
    ])
    ->withAvg('reviewsReceived as average_rating', 'rating')
    ->orderByDesc('services_count')
    ->take(6)
    ->get();

    return view('dashboard', compact('services', 'categories', 'q', 'category_id', 'min_rating', 'available', 'topStudents'));
}

    public function services(Request $request): JsonResponse
{
    $q = $request->string('q')->toString();
    $categoryId = $request->integer('category_id');
    $minRating = $request->integer('min_rating');
    $availableOnly = $request->boolean('available_only', false);

    $query = StudentService::query()
        ->where('is_active', true)
        ->with(['category', 'helper' => function ($query) {
            $query->select(['id', 'name', 'role', 'is_available']);
        }]);

    if ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('title', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%");
        });
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($availableOnly) {
        $query->whereHas('student', function ($sub) {
            $sub->where('is_available', true);
        });
    }

    if ($minRating) {
        $query->whereRaw('(
            select avg(rating)
            from reviews r
            where r.reviewee_id = student_services.user_id
        ) >= ?', [$minRating]);
    }

    $query->orderByDesc('id');

    $services = $query->get();

    $result = $services->map(function ($svc) {
        $student = $svc->student;
        return [
            'id' => $svc->id,
            'title' => $svc->title,
            'description' => $svc->description,
            'basic_price' => $svc->basic_price,
            'category' => $svc->category,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'badge' => $student->trust_badge,
                'is_available' => (bool) $student->is_available,
                'average_rating' => $student->average_rating,
            ],
        ];
    });

    return response()->json(['services' => $result], 200);
}

public function switchMode(Request $request)
{
    $user = Auth::user();

    // Only helpers can switch modes
    if ($user->role !== 'helper') {
        return back()->with('error', 'Unauthorized action.');
    }

    // Get current mode (default to 'seller' for helpers if not set)
    $currentMode = session('view_mode', 'seller');

    if ($currentMode === 'seller') {
        // Switch to Buying Mode
        session(['view_mode' => 'buyer']);
        return redirect()->route('dashboard'); // Redirect to Browse Services/Home
    } else {
        // Switch to Selling Mode
        session(['view_mode' => 'seller']);
        return redirect()->route('students.index'); // Redirect to Helper Dashboard
    }
}

}