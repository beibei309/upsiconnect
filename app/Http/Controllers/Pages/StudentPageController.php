<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentService;
use Illuminate\Support\Facades\Auth;

class StudentPageController extends Controller
{
    public function profile(User $user)
    {
        $services = StudentService::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        $canChatOrReport = Auth::check() && Auth::id() !== $user->id;

        return view('students.profile', [
            'student' => $user,
            'services' => $services,
            'canActions' => $canChatOrReport,
        ]);
    }
}