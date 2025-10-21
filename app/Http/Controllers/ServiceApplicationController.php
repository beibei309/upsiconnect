<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceApplication;
use App\Models\User;

class ServiceApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    /**
     * Mark service as completed by current user
     */
    public function markCompleted(ServiceApplication $serviceApplication)
    {
        $user = auth()->user();
        
        // Check if user can mark this application as completed
        if (!$serviceApplication->canBeMarkedCompletedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot mark this service as completed.'
            ], 403);
        }

        try {
            // Mark as completed by the appropriate party
            if ($user->id === $serviceApplication->user_id) {
                $serviceApplication->markCompletedByCustomer();
                $message = 'You have marked this service as completed. Waiting for provider confirmation.';
            } else {
                $serviceApplication->markCompletedByProvider();
                $message = 'You have marked this service as completed. Waiting for customer confirmation.';
            }

            // Check if both parties have completed
            $serviceApplication->refresh();
            if ($serviceApplication->isFullyCompleted()) {
                $message = 'Service has been marked as completed by both parties! You can now leave a review.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'fully_completed' => $serviceApplication->isFullyCompleted()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking the service as completed.'
            ], 500);
        }
    }

    /**
     * Show the service application form
     */
    public function create()
    {
        // Only allow community users to apply for services
        if (Auth::user()->role !== 'community') {
            return redirect()->route('dashboard')->with('error', 'Only community members can apply for services.');
        }

        return view('services.apply');
    }

    /**
     * Store a new service application
     */
    public function store(Request $request)
    {
        // Only allow community users to apply for services
        if (Auth::user()->role !== 'community') {
            return response()->json(['error' => 'Only community members can apply for services.'], 403);
        }

        // Check if user is verified
        if (!Auth::user()->isVerifiedPublic()) {
            return response()->json(['error' => 'Please complete your account verification first.'], 403);
        }

        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget_range' => 'nullable|string|max:255',
            'timeline' => 'required|string|max:255',
            'contact_methods' => 'nullable|array',
            'contact_methods.*' => 'string|in:platform_chat,email,phone'
        ]);

        // Convert contact methods array to JSON
        $validated['contact_methods'] = json_encode($request->contact_methods ?? []);
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'open';

        $application = ServiceApplication::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service application submitted successfully! Students will be notified and can contact you soon.',
            'application_id' => $application->id
        ]);
    }

    /**
     * Show all applications for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'community') {
            // Show applications submitted by this community user
            $applications = ServiceApplication::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('services.applications.index', compact('applications'));
        } else {
            // For students, show applications they can respond to
            $applications = ServiceApplication::where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('services.applications.browse', compact('applications'));
        }
    }

    /**
     * Show a specific application
     */
    public function show(ServiceApplication $application)
    {
        $user = Auth::user();
        
        // Community users can only see their own applications
        if ($user->role === 'community' && $application->user_id !== $user->id) {
            abort(403, 'You can only view your own applications.');
        }

        return view('services.applications.show', compact('application'));
    }

    /**
     * Apply for a service from within a chat conversation
     */
    public function applyFromChat(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'service_id' => ['required', 'exists:student_services,id'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $conversation = \App\Models\Conversation::findOrFail($data['conversation_id']);
        $service = \App\Models\StudentService::findOrFail($data['service_id']);
        $user = Auth::user();

        // Check if user is the customer in this conversation
        if ($user->id !== $conversation->customer_id) {
            return response()->json(['error' => 'Only the customer can apply for services.'], 403);
        }

        // Check if service belongs to the student in this conversation
        if ($service->user_id !== $conversation->student_id) {
            return response()->json(['error' => 'Service does not belong to the provider in this conversation.'], 400);
        }

        // Check if service is available
        if ($service->status !== 'available') {
            return response()->json(['error' => 'This service is not available.'], 400);
        }

        // Check if there's already a pending application for this service from this user
        $existingApplication = ServiceApplication::where([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'status' => 'pending'
        ])->first();

        if ($existingApplication) {
            return response()->json(['error' => 'You already have a pending application for this service.'], 400);
        }

        $application = ServiceApplication::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'conversation_id' => $conversation->id,
            'title' => 'Application for: ' . $service->title,
            'service_type' => $service->category->name ?? 'General',
            'description' => $data['message'] ?? 'Application submitted from chat',
            'budget_range' => $service->price_range ?? 'As discussed',
            'timeline' => 'As discussed',
            'contact_methods' => json_encode(['platform_chat']),
            'status' => 'pending',
        ]);

        return response()->json([
            'application' => $application->load(['user', 'service']),
            'success' => true,
            'message' => 'Service application submitted successfully!'
        ], 201);
    }

    /**
     * Accept a service application from chat
     */
    public function acceptFromChat(Request $request, ServiceApplication $application)
    {
        $user = Auth::user();

        // Check if user is the service provider
        if ($user->id !== $application->service->user_id) {
            return response()->json(['error' => 'You are not authorized to accept this application.'], 403);
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return response()->json(['error' => 'This application is no longer pending.'], 400);
        }

        $application->update(['status' => 'accepted']);
        
        // Make service unavailable
        $application->service->update(['status' => 'busy']);

        return response()->json([
            'success' => true,
            'message' => 'Application accepted successfully! Service is now unavailable to others.'
        ]);
    }

    /**
     * Decline a service application from chat
     */
    public function declineFromChat(Request $request, ServiceApplication $application)
    {
        $user = Auth::user();

        // Check if user is the service provider
        if ($user->id !== $application->service->user_id) {
            return response()->json(['error' => 'You are not authorized to decline this application.'], 403);
        }

        // Check if application is still pending
        if ($application->status !== 'pending') {
            return response()->json(['error' => 'This application is no longer pending.'], 400);
        }

        $application->update(['status' => 'declined']);

        return response()->json([
            'success' => true,
            'message' => 'Application declined.'
        ]);
    }
}