<?php

namespace App\Http\Controllers;
use App\Models\StudentService;
use App\Models\User;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\ServiceRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();

    // Range handling (default last 30 days)
    $range = $request->get('range', '30days'); // possible: 30days, 3months, yearly

    if ($range === '3months') {
        $start = Carbon::now()->subMonths(3);
        $interval = 'day'; // still show daily within range
    } elseif ($range === 'yearly') {
        $start = Carbon::now()->subYear();
        $interval = 'month'; // for year show monthly aggregation (more practical)
    } else {
        $start = Carbon::now()->subDays(29); // last 30 days inclusive
        $interval = 'day';
    }

    // Build labels ($dates) depending on interval
    $labels = [];
    $period = [];

    if ($interval === 'day') {
        $days = $start->diffInDays(Carbon::now());
        for ($i = 0; $i <= $days; $i++) {
            $d = $start->copy()->addDays($i);
            $labels[] = $d->format('M j, Y'); // "Nov 15, 2025"
            $period[] = $d->format('Y-m-d');
        }
    } else { // month
        $months = $start->diffInMonths(Carbon::now());
        for ($i = 0; $i <= $months; $i++) {
            $m = $start->copy()->addMonths($i);
            $labels[] = $m->format('M Y'); // "Nov 2025"
            $period[] = $m->format('Y-m'); // use Y-m for grouping
        }
    }

    // Initialize arrays
    $sales = [];
    $cancelled = [];
    $completedDaily = [];
    $newOrders = [];

    // Fill arrays by iterating period buckets
    foreach ($period as $p) {
        if ($interval === 'day') {
            // sales = sum price for completed on that date
            $sales[] = ServiceRequest::whereDate('created_at', $p)
                        ->where('provider_id', $user->id)
                        ->where('status', 'completed')
                        ->sum('offered_price'); // or 'price' as your column

            $cancelled[] = ServiceRequest::whereDate('created_at', $p)
                        ->where('provider_id', $user->id)
                        ->where('status', 'cancelled')
                        ->sum('offered_price');

            $completedDaily[] = ServiceRequest::whereDate('created_at', $p)
                        ->where('provider_id', $user->id)
                        ->where('status', 'completed')
                        ->count();

            $newOrders[] = ServiceRequest::whereDate('created_at', $p)
                        ->where('provider_id', $user->id)
                        ->where('status', 'pending')
                        ->count();
        } else { // month aggregation
            $sales[] = ServiceRequest::whereYear('created_at', substr($p,0,4))
                        ->whereMonth('created_at', substr($p,5,2))
                        ->where('provider_id', $user->id)
                        ->where('status', 'completed')
                        ->sum('offered_price');

            $cancelled[] = ServiceRequest::whereYear('created_at', substr($p,0,4))
                        ->whereMonth('created_at', substr($p,5,2))
                        ->where('provider_id', $user->id)
                        ->where('status', 'cancelled')
                        ->sum('offered_price');

            $completedDaily[] = ServiceRequest::whereYear('created_at', substr($p,0,4))
                        ->whereMonth('created_at', substr($p,5,2))
                        ->where('provider_id', $user->id)
                        ->where('status', 'completed')
                        ->count();

            $newOrders[] = ServiceRequest::whereYear('created_at', substr($p,0,4))
                        ->whereMonth('created_at', substr($p,5,2))
                        ->where('provider_id', $user->id)
                        ->where('status', 'pending')
                        ->count();
        }
    }

    // Other small stats you already had
    $averageRating = $user->reviewsReceived()->avg('rating');
    $averageRating = $averageRating ? round($averageRating, 1) : '-';

    $completedOrders = ServiceRequest::where('provider_id', $user->id)
                        ->where('status', 'completed')
                        ->count();

    return view('students.index', compact(
        'user',
        'averageRating',
        'completedOrders',
        'labels',
        'sales',
        'cancelled',
        'completedDaily',
        'newOrders',
        'range'
    ));
}

     public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'profile_photo' => 'nullable|image|max:4096',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:500',
            'work_experience_message' => 'nullable|string|max:1000',
            'work_experience_file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:4096',
        ]);

        $user = auth()->user(); // logged-in user

        // Profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/profile', $filename, 'public');
            $user->profile_photo_path = $path;
        }

        // Update basic fields
        $user->bio = $validated['bio'] ?? $user->bio;
        $user->skills = $validated['skills'] ?? $user->skills;
        $user->work_experience_message = $validated['work_experience_message'] ?? $user->work_experience_message;

        // Handle work experience file
        if ($request->hasFile('work_experience_file')) {
            $file = $request->file('work_experience_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/work_experience', $filename, 'public');

            $user->work_experience_file = 'uploads/work_experience/' . $filename;
        }

        // **Mark user as helper**
        $user->role = 'helper'; // or $user->status = 'helper', depending on your DB column
        $user->save();

        return redirect()->route('students.create')
                        ->with('status', 'Profile updated successfully!')
                        ->with('ready_to_help', true);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('students.edit-profile', compact('user'));
        
    }


    public function update(Request $request)
    {
        $user = Auth::user();


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:500',
            'is_available' => 'nullable', 
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096', 
        ]);

        $user->name = $validated['name'];
        $user->faculty = $validated['faculty'] ?? $user->faculty;
        $user->course = $validated['course'] ?? $user->course;
        $user->bio = $validated['bio'];
        $user->skills = $validated['skills'];


        $user->is_available = $request->has('is_available') ? true : false;


        if ($request->hasFile('profile_photo_path')) {
            
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                 Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo_path');
            $filename = time() . '_' . $file->getClientOriginalName(); 

            $path = $file->storeAs('uploads/profile', $filename, 'public'); 
       
            $user->profile_photo_path = 'uploads/profile/' . $filename;
        }

        $user->save();

        return redirect()
            ->route('students.index', $user->id)
            ->with('success', 'Profile updated successfully!');
    }

    public function profile(User $user)
    {
     
        $user->load([
            'reviewsReceived.reviewer', // Supaya boleh tunjuk gambar/nama orang yang review
            'reviewsReceived.service'   // Supaya boleh tunjuk review tu untuk service apa
        ]);
        

       
        $services = $user->services()
                         ->where('is_active', true) // Hanya tunjuk yang active
                         ->latest()
                         ->get();

        $servicesActiveCount = $services->count();

        $reviews = $user->reviewsReceived()->latest()->get();


        return view('students.profile', compact(
            'user',
            'services',
            'servicesActiveCount',
            'reviews'
        ));
    }

}