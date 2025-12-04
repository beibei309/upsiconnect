<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class StudentsController extends Controller
{
    public function index()
    {
        // Fetch the current user's data from the users table
        $user = auth()->user();

        // Pass user data to the view
        return view('students.index', compact('user'));
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

}