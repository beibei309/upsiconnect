<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function index() {
        return view('onboarding.students_verification'); 
    }

public function uploadPhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|max:4096'
    ]);

    $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            // Delete old file if exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/profile', $filename, 'public');
            $user->profile_photo_path = $path;
        $user->save();
    }

    return redirect()->back()->with('status', 'Profile photo uploaded!');
}

}
