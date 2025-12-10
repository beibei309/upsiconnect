<x-app-layout>
@section('content')
<br><br><br>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Account Settings</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your profile details and security preferences.</p>
        </div>

        <div x-data="{ activeTab: 'profile' }" class="flex flex-col lg:flex-row gap-6 items-start">
            
            <aside class="lg:w-72 flex-shrink-0 w-full">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden sticky top-8">
                    <nav class="flex flex-col">
                        <button @click="activeTab = 'profile'" 
                            :class="activeTab === 'profile' ? 'bg-indigo-50 border-l-4 border-indigo-600 text-indigo-700' : 'border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                            class="flex items-center px-6 py-4 text-sm font-medium transition-all duration-200 focus:outline-none w-full text-left">
                            <svg :class="activeTab === 'profile' ? 'text-indigo-600' : 'text-gray-400'" 
                                 class="mr-3 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile Information
                        </button>

                        <div class="border-t border-gray-100"></div>

                        <button @click="activeTab = 'password'" 
                            :class="activeTab === 'password' ? 'bg-indigo-50 border-l-4 border-indigo-600 text-indigo-700' : 'border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                            class="flex items-center px-6 py-4 text-sm font-medium transition-all duration-200 focus:outline-none w-full text-left">
                            <svg :class="activeTab === 'password' ? 'text-indigo-600' : 'text-gray-400'"
                                 class="mr-3 h-5 w-5 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password & Security
                        </button>
                    </nav>
                </div>
            </aside>

            <div class="flex-1 w-full">
                
                <div x-show="activeTab === 'profile'" x-cloak 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     class="bg-white shadow-sm rounded-xl border border-gray-200">
                    
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 rounded-t-xl">
                        <h3 class="text-lg font-bold text-gray-900">Profile Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                    </div>
                    
                    <div class="p-6 md:p-8">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            @method('patch')

                            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100" x-data="{ previewUrl: null }">
                                <div class="relative group">
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 ring-1 ring-gray-200">
                                        <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover" style="display: none;">
                                        
                                        @if(auth()->user()->profile_photo_path)
                                            <img x-show="!previewUrl" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div x-show="!previewUrl" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-3xl font-bold">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <label for="profile_photo" class="absolute bottom-0 right-0 p-2 bg-white rounded-full shadow-md border border-gray-200 cursor-pointer hover:bg-gray-50 hover:text-indigo-600 text-gray-500 transition-all transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </label>
                                    <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" @change="previewUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                                </div>
                                <div class="text-center sm:text-left">
                                    <h4 class="text-base font-semibold text-gray-900">Profile Photo</h4>
                                    <p class="text-sm text-gray-500 mt-1">Accepts JPG, GIF or PNG. Max size 1MB.</p>
                                    @error('profile_photo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2">
                                <div class="col-span-2 sm:col-span-1">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-2 text-sm text-yellow-700 bg-yellow-50 p-2 rounded border border-yellow-200">
                                            Your email is unverified.
                                            <button form="send-verification" class="underline font-bold ml-1 hover:text-yellow-900">Resend Link</button>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+60123456789"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                @if($user->role === 'student')
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                                        <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $user->student_id) }}" 
                                            class="block w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 shadow-sm sm:text-sm py-2.5 cursor-not-allowed" 
                                            {{ $user->student_id ? 'readonly' : '' }}>
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label for="faculty" class="block text-sm font-medium text-gray-700 mb-1">Faculty</label>
                                        <input type="text" name="faculty" id="faculty" value="{{ old('faculty', $user->faculty) }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    </div>

                                    <div class="col-span-2">
                                        <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course / Program</label>
                                        <input type="text" name="course" id="course" value="{{ old('course', $user->course) }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    </div>
                                @endif

                                <div class="col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                    <textarea id="bio" name="bio" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2" placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio ?? '') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500 text-right">Max 500 characters.</p>
                                </div>
                            </div>

                            <div class="pt-6 flex items-center justify-end border-t border-gray-100 gap-4">
                                @if (session('status') === 'profile-updated')
                                    <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Saved
                                    </span>
                                @endif
                                <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="activeTab === 'password'" x-cloak 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     class="bg-white shadow-sm rounded-xl border border-gray-200">
                    
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 rounded-t-xl">
                        <h3 class="text-lg font-bold text-gray-900">Update Password</h3>
                        <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    
                    <div class="p-6 md:p-8">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
                            @csrf
                            @method('put')

                            <div>
                                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" name="current_password" id="update_password_current_password" autocomplete="current-password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                @error('current_password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" name="password" id="update_password_password" autocomplete="new-password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                @error('password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                @error('password_confirmation', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-6 flex items-center justify-end border-t border-gray-100 gap-4">
                                @if (session('status') === 'password-updated')
                                    <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Updated
                                    </span>
                                @endif
                                <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</x-app-layout>