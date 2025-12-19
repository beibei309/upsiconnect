<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
                'unique:'.User::class,
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->role === 'student') {
                        if (!str_ends_with($value, '@siswa.upsi.edu.my')) {
                            $fail('Students must register with their @siswa.upsi.edu.my email.');
                        }
                    } elseif ($request->community_type === 'staff') { // Check community_type for staff
                        // Allowed staff pattern (Central + Faculties + Depts)
                        $pattern = '/^[a-zA-Z0-9._%+-]+@(upsi\.edu\.my|fsskj\.upsi\.edu\.my|fpm\.upsi\.edu\.my|fsmt\.upsi\.edu\.my|fskik\.upsi\.edu\.my|meta\.upsi\.edu\.my|fbk\.upsi\.edu\.my|fpe\.upsi\.edu\.my|fmsp\.upsi\.edu\.my|ftv\.upsi\.edu\.my|fsk\.upsi\.edu\.my|bendahari\.upsi\.edu\.my|ict\.upsi\.edu\.my)$/';
                        
                        if (!preg_match($pattern, $value)) {
                            $fail('Staff must use a valid UPSI staff email (e.g., @upsi.edu.my or faculty subdomain).');
                        }
                    }
                }
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,community'],
            'phone' => ['required', 'string', 'max:20'],
            'student_id' => ['required_if:role,student', 'nullable', 'string', 'max:20'],
            'community_type' => ['nullable', 'in:public,staff'],
            // staff_email removed - use main email
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            // 'staff_email' => $request->staff_email, // Removed as we use main email
            'verification_status' => 'pending', // Always pending until email verified
            'is_available' => $request->role === 'student' ? true : false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
