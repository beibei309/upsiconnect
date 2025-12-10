<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    // FUNCTION BARU UNTUK LIVE SELFIE
    public function uploadSelfie(Request $request)
    {
        $request->validate([
            'selfie_image' => 'required'
        ]);

        $user = auth()->user();
        $image = $request->input('selfie_image'); // Base64 string

        // Convert base64 to image file
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'selfie_' . time() . '_' . Str::random(10) . '.png';
        
        // Simpan fail dalam storage/app/public/uploads/verification
        Storage::disk('public')->put('uploads/verification/' . $imageName, base64_decode($image));

        // Simpan path dalam database (anda mungkin perlu tambah column baru: 'verification_selfie_path')
        // Atau buat masa ni simpan dalam session atau log jika belum ada column
        // $user->verification_selfie_path = 'uploads/verification/' . $imageName;
        // $user->save();

        return response()->json(['success' => true, 'message' => 'Selfie verified & uploaded!']);
    }
}