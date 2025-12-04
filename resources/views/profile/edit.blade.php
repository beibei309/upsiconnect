@extends('layouts.helper')

@section('content')
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <br><br>
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
                <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
            </div>

            <div x-data="{ activeTab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Settings Navigation -->
                <div class="lg:col-span-1">
                    <nav class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-8">
                        <ul class="space-y-2">
                            <li>
                                <button @click="activeTab = 'profile'" 
                                        :class="activeTab === 'profile' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'text-gray-700 hover:bg-gray-50'"
                                        class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile Information
                                </button>
                            </li>
                            <li>
                                <button @click="activeTab = 'password'" 
                                        :class="activeTab === 'password' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'text-gray-700 hover:bg-gray-50'"
                                        class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Password & Security
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Settings Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Profile Information Section -->
                    <div x-show="activeTab === 'profile'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                            <p class="text-gray-600 mt-1">Update your account's profile information and email address.</p>
                        </div>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Profile Avatar with instant preview -->
                            <div x-data="{ previewUrl: null }" class="flex items-center space-x-6">
                                <div>
                                    <!-- Live preview (shows immediately when a file is selected) -->
                                    <img x-show="previewUrl" x-cloak :src="previewUrl" alt="{{ auth()->user()->name }}" class="w-20 h-20 rounded-full object-cover ring-1 ring-gray-200">

                                    <!-- Existing avatar or initial fallback (shown when no preview selected) -->
                                    @if(auth()->user()->profile_photo_path)
                                        <img x-show="!previewUrl" x-cloak src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-20 h-20 rounded-full object-cover ring-1 ring-gray-200">
                                    @else
                                        <div x-show="!previewUrl" x-cloak class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label for="profile_photo" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors cursor-pointer w-fit">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Change Avatar
                                        <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" @change="previewUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">JPG, GIF or PNG. 1MB max.</p>
                                    <p x-show="previewUrl" x-cloak class="text-xs text-gray-500 mt-1">Preview shown. Click <span class="font-medium">Save Changes</span> to apply.</p>
                                    @error('profile_photo')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       value="{{ old('name', $user->name) }}" 
                                       required 
                                       autofocus 
                                       autocomplete="name"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required 
                                       autocomplete="username"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            Your email address is unverified.
                                            <button form="send-verification" class="underline text-yellow-700 hover:text-yellow-900 font-medium">
                                                Click here to re-send the verification email.
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 text-sm text-green-600 font-medium">
                                                A new verification link has been sent to your email address.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input id="phone" 
                                       name="phone" 
                                       type="text" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       placeholder="+60123456789"
                                       autocomplete="tel"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Student-specific fields -->
                            @if($user->role === 'student')
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                    <input id="student_id" 
                                           name="student_id" 
                                           type="text" 
                                           value="{{ old('student_id', $user->student_id) }}" 
                                           placeholder="e.g., D20211234567"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                                           {{ $user->student_id ? 'readonly' : '' }}>
                                    <p class="mt-1 text-sm text-gray-500">Your UPSI student ID. Cannot be changed once set.</p>
                                    @error('student_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">Faculty</label>
                                    <input id="faculty" 
                                           name="faculty" 
                                           type="text" 
                                           value="{{ old('faculty', $user->faculty) }}" 
                                           placeholder="e.g., Faculty of Computing and Meta-Technology"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('faculty')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course/Program</label>
                                    <input id="course" 
                                           name="course" 
                                           type="text" 
                                           value="{{ old('course', $user->course) }}" 
                                           placeholder="e.g., Bachelor of Computer Science"
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('course')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- Staff email field for community users -->
                            @if($user->isCommunity())
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-blue-900 mb-2">Staff Verification</h4>
                                            <p class="text-sm text-blue-700 mb-3">Are you a UPSI staff member? Verify with your official UPSI email to get the "Staf UPSI Rasmi" badge.</p>
                                            
                                            @if(!$user->isVerifiedStaff())
                                                <label for="staff_email" class="block text-sm font-medium text-blue-900 mb-2">Staff Email (@upsi.edu.my)</label>
                                                <input id="staff_email" 
                                                       name="staff_email" 
                                                       type="email" 
                                                       value="{{ old('staff_email', $user->staff_email) }}" 
                                                       placeholder="yourname@upsi.edu.my"
                                                       class="block w-full px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <p class="mt-1 text-xs text-blue-600">A verification email will be sent to this address.</p>
                                                @error('staff_email')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            @else
                                                <div class="flex items-center text-green-700">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm font-medium">Verified as UPSI Staff ({{ $user->staff_email }})</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Bio Field -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="4" 
                                          placeholder="Tell us about yourself..."
                                          maxlength="500"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('bio', $user->bio ?? '') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Brief description for your profile. Maximum 500 characters.</p>
                                @error('bio')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Save Button -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div>
                                    @if (session('status') === 'profile-updated')
                                        <p class="text-sm text-green-600 font-medium">Profile updated successfully!</p>
                                    @endif
                                </div>
                                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Section -->
                    <div x-show="activeTab === 'password'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
                            <p class="text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <!-- Current Password -->
                            <div>
                                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input id="update_password_current_password" 
                                       name="current_password" 
                                       type="password" 
                                       autocomplete="current-password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('current_password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input id="update_password_password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="new-password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <input id="update_password_password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       autocomplete="new-password"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password_confirmation', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Save Button -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div>
                                    @if (session('status') === 'password-updated')
                                        <p class="text-sm text-green-600 font-medium">Password updated successfully!</p>
                                    @endif
                                </div>
                                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection