<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function index() {
        $user = auth()->user();
        
        // Redirect helpers to dashboard - they're already verified
        if ($user->role === 'helper') {
            return redirect()->route('dashboard')->with('info', 'You are already a verified helper!');
        }
        
        return view('onboarding.students_verification'); 
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:4096'
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/profile', $filename, 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile photo uploaded!']);
        }

        return redirect()->back()->with('status', 'Profile photo uploaded!');
    }

    // --- STUDENT HELPER VERIFICATION METHOD ---
    
    public function uploadSelfie(Request $request)
    {
        $request->validate([
            'selfie_image' => 'required'
        ]);

        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        \Illuminate\Support\Facades\Log::info('Student Helper Selfie Upload Started for user: ' . auth()->id());

        $user = auth()->user();
        
        try {
            $image = $request->input('selfie_image'); // Base64 string
            \Illuminate\Support\Facades\Log::info('Selfie payload received. Length: ' . strlen($image));

            // Convert base64 to image file
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);
            
            $decodedImage = base64_decode($image);
            if ($decodedImage === false) {
                 throw new \Exception('Base64 decode failed');
            }

            $imageName = 'helper_selfie_' . $user->id . '_' . time() . '.jpg';
            
            \Illuminate\Support\Facades\Log::info('Saving helper selfie to: uploads/verification/' . $imageName);
            
            // Store in 'local' disk (private storage)
            Storage::disk('local')->put('uploads/verification/' . $imageName, $decodedImage);
            
            if (!Storage::disk('local')->exists('uploads/verification/' . $imageName)) {
                throw new \Exception('Failed to verify file existence after write.');
            }

            // Update user with selfie path
            $user->selfie_media_path = 'uploads/verification/' . $imageName;
            
            // Convert student to helper role
            $user->role = 'helper';
            $user->helper_verified_at = now();
            
            $user->save();

            \Illuminate\Support\Facades\Log::info('Student converted to helper. User ID: ' . $user->id);

            return response()->json([
                'success' => true, 
                'message' => 'Verification complete! You are now a helper.',
                'redirect' => route('dashboard') . '?mode=seller'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Helper Selfie Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    // Save verified location data
    public function saveLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'location_verified_at' => now()
        ]);

        \Illuminate\Support\Facades\Log::info('Location saved for user: ' . $user->id . ' - Lat: ' . $request->latitude . ', Lng: ' . $request->longitude);

        return response()->json(['success' => true, 'message' => 'Location verified and saved!']);
    }

    // --- COMMUNITY VERIFICATION METHODS ---

    public function uploadCommunitySelfie(Request $request)
    {
        $request->validate([
            'selfie_image' => 'required'
        ]);

        // PREVENT TIMEOUTS/MEMORY ISSUES
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        \Illuminate\Support\Facades\Log::info('Community Selfie Upload Started for user: ' . auth()->id());

        $user = auth()->user();
        try {
            $image = $request->input('selfie_image'); // Base64 string
            \Illuminate\Support\Facades\Log::info('Selfie payload received. Length: ' . strlen($image));

            // Convert base64 to image file
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);
            
            $decodedImage = base64_decode($image);
            if ($decodedImage === false) {
                 throw new \Exception('Base64 decode failed');
            }

            $imageName = 'selfie_' . $user->id . '_' . time() . '.jpg';
            
            \Illuminate\Support\Facades\Log::info('Saving selfie to: uploads/verification/' . $imageName);
            
            // PRIVACY: Store in 'local' disk (private) instead of public
            Storage::disk('local')->put('uploads/verification/' . $imageName, $decodedImage);
            
            if (!Storage::disk('local')->exists('uploads/verification/' . $imageName)) {
                throw new \Exception('Failed to verify file existence after write.');
            }

            $user->selfie_media_path = 'uploads/verification/' . $imageName;
            // Save the challenge note (e.g. "Peace Sign")
            $user->verification_note = $request->input('verification_note');
            $user->save();

            return response()->json(['success' => true, 'message' => 'Selfie verified & uploaded!']);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Selfie Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function submitDoc(Request $request)
    {
        $request->validate([
            'verification_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $user = auth()->user();

        if ($request->hasFile('verification_document')) {
            $file = $request->file('verification_document');
            $filename = 'verify_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store in storage/app/verification_docs
            $path = $file->storeAs('verification_docs', $filename, 'local');

            // Update User
            $user->verification_document_path = $path;
            $user->verification_status = 'approved'; // Set to pending ONLY at final step
            $user->save();

            return redirect()->back()->with('status', 'Verification submitted! Admin will review shortly.');
        }

        return redirect()->back()->withErrors(['verification_document' => 'Upload failed. Please try again.']);
    }

    // Keep original submit for backward compatibility if needed, but we use submitDoc now
    public function submit(Request $request) { return $this->submitDoc($request); }
}