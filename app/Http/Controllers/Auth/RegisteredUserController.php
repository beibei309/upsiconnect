<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->role === 'student' && !str_ends_with($value, '@siswa.upsi.edu.my')) {
                        $fail('Student must use @siswa.upsi.edu.my email');
                    }
                },
            ],
                        
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,community'],
            'phone' => ['required', 'string', 'max:20'],
            'student_id' => ['required_if:role,student', 'nullable', 'string', 'max:20'],
            // Optional: used for instant verification; name retained for compatibility
            'community_type' => ['nullable', 'in:public,staff'],
            'staff_email' => [
                'nullable', 
                'email',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        if ($request->role === 'student') {
                            if (!str_ends_with($value, '@siswa.upsi.edu.my')) {
                                $fail('Student email must end with @siswa.upsi.edu.my');
                            }
                        } elseif ($request->role === 'community' && $request->community_type === 'staff') {
                            if (!preg_match('/@([a-zA-Z]+\.)?upsi\.edu\.my$/', $value)) {
                                $fail('Staff email must be in format @upsi.edu.my or @faculty.upsi.edu.my');
                            }
                        }
                    }
                }
            ],
        ]);

        // Set verification status based on role
        $verificationStatus = 'pending';
        $publicVerifiedAt = null;
        $staffVerifiedAt = null;
        
        if ($request->role === 'student') {
            if ($request->role === 'student') {
                // email already validated
                $verificationStatus = 'approved';
                $publicVerifiedAt = now();
            }

            
        } elseif ($request->role === 'community') {
            // Staff are auto-verified if they have valid UPSI email
            if ($request->community_type === 'staff' && $request->staff_email && preg_match('/@([a-zA-Z]+\.)?upsi\.edu\.my$/', $request->staff_email)) {
                $verificationStatus = 'approved';
                $staffVerifiedAt = now();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,                           
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'staff_email' => $request->staff_email,
            'verification_status' => $verificationStatus,
            'public_verified_at' => $publicVerifiedAt,
            'staff_verified_at' => $staffVerifiedAt,
            'is_available' => $request->role === 'student' ? true : false,
        ]);

        if ($user->role === 'student') {
             StudentStatus::create([
            'student_id'      => $user->id,              // FK to users
            'matric_no'       => $user->student_id,      // SAME value
            'semester'        => null,
            'status'          => 'active',            
            'graduation_date' => null,
           'effective_date'  => now(),   
        ]);
    }


        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
