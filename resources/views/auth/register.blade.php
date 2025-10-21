<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
        <p class="text-gray-600">Join UpsiConnect and start connecting with students</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ role: '{{ old('role', 'student') }}', communityType: '{{ old('community_type', 'public') }}' }">
        @csrf

        <!-- Role Selection -->
        <div>
            <x-input-label for="role" :value="__('I am registering as')" class="block text-sm font-medium text-gray-700 mb-2" />
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="student" x-model="role" class="sr-only">
                    <div class="border-2 rounded-lg p-4 text-center transition-all" 
                         :class="role === 'student' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                        <div class="text-lg font-semibold text-gray-900">Student</div>
                        <div class="text-sm text-gray-600 mt-1">Provide services to community</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Pelajar UPSI Terkini
                            </span>
                        </div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="community" x-model="role" class="sr-only">
                    <div class="border-2 rounded-lg p-4 text-center transition-all" 
                         :class="role === 'community' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                        <div class="text-lg font-semibold text-gray-900">Community</div>
                        <div class="text-sm text-gray-600 mt-1">Request services from students</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pengguna Disahkan
                            </span>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="name" 
                         class="input input-bordered w-full" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autofocus 
                         autocomplete="name" 
                         placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="email" 
                         class="input input-bordered w-full" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autocomplete="username" 
                         placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="phone" 
                         class="input input-bordered w-full" 
                         type="tel" 
                         name="phone" 
                         :value="old('phone')" 
                         required 
                         placeholder="Enter your phone number (e.g., +60123456789)" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Student-specific fields -->
        <div x-show="role === 'student'" x-transition>
            <!-- Student ID -->
            <div class="mb-4">
                <x-input-label for="student_id" :value="__('Student ID')" class="block text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="student_id" 
                             class="input input-bordered w-full" 
                             type="text" 
                             name="student_id" 
                             :value="old('student_id')" 
                             placeholder="Enter your UPSI student ID" />
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>

            <!-- UPSI Email -->
            <div>
                <x-input-label for="staff_email" :value="__('UPSI Student Email')" class="block text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="staff_email" 
                             class="input input-bordered w-full" 
                             type="email" 
                             name="staff_email" 
                             :value="old('staff_email')" 
                             placeholder="yourname@siswa.upsi.edu.my" />
                <p class="text-xs text-gray-500 mt-1">Use your official UPSI student email for instant verification</p>
                <x-input-error :messages="$errors->get('staff_email')" class="mt-2" />
            </div>
        </div>

        <!-- Community-specific fields -->
        <div x-show="role === 'community'" x-transition>
            <!-- Community Type Selection -->
            <div class="mb-4">
                <x-input-label :value="__('Community Type')" class="block text-sm font-medium text-gray-700 mb-2" />
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="community_type" value="public" class="radio radio-primary mr-2" x-model="communityType">
                        <span class="text-sm">Public User</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="community_type" value="staff" class="radio radio-primary mr-2" x-model="communityType">
                        <span class="text-sm">UPSI Staff/Lecturer</span>
                    </label>
                </div>
            </div>

            <!-- Staff Email (for lecturers) -->
            <div x-show="communityType === 'staff'" x-transition>
                <x-input-label for="staff_email_community" :value="__('UPSI Staff Email')" class="block text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="staff_email_community" 
                             class="input input-bordered w-full" 
                             type="email" 
                             name="staff_email" 
                             :value="old('staff_email')" 
                             placeholder="yourname@upsi.edu.my" />
                <p class="text-xs text-gray-500 mt-1">Use your official UPSI staff email (e.g., @upsi.edu.my, @fskik.upsi.edu.my, @fpm.upsi.edu.my) for instant verification</p>
                <x-input-error :messages="$errors->get('staff_email')" class="mt-2" />
            </div>

            <!-- Verification notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Verification Required</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p x-show="communityType === 'staff'">Staff with valid UPSI email will be auto-verified. Others require manual verification.</p>
                            <p x-show="communityType === 'public'">Public accounts require manual verification by our admin team.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="password" 
                         class="input input-bordered w-full"
                         type="password"
                         name="password"
                         required 
                         autocomplete="new-password" 
                         placeholder="Create a strong password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="password_confirmation" 
                         class="input input-bordered w-full"
                         type="password"
                         name="password_confirmation" 
                         required 
                         autocomplete="new-password" 
                         placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <input id="terms" 
                   type="checkbox" 
                   class="checkbox checkbox-primary mt-1" 
                   required>
            <label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the 
                <a href="{{ route('terms') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Terms of Service</a> 
                and 
                <a href="{{ route('privacy') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="btn btn-primary w-full">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Already have an account?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-outline w-full">
                Sign In Instead
            </a>
        </div>
    </form>

    <!-- Back to Home -->
    <div class="mt-6 text-center">
        <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
            ← Back to Home
        </a>
    </div>
</x-guest-layout>
