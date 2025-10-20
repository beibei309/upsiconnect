<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
                <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Settings Navigation -->
                <div class="lg:col-span-1">
                    <nav class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-8" x-data="{ activeTab: 'profile' }">
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
                            <li>
                                <button @click="activeTab = 'danger'" 
                                        :class="activeTab === 'danger' ? 'bg-red-50 text-red-700 border-red-200' : 'text-gray-700 hover:bg-gray-50'"
                                        class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-transparent transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Danger Zone
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Settings Content -->
                <div class="lg:col-span-3 space-y-8" x-data="{ activeTab: 'profile' }">
                    <!-- Profile Information Section -->
                    <div x-show="activeTab === 'profile'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                            <p class="text-gray-600 mt-1">Update your account's profile information and email address.</p>
                        </div>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Profile Avatar -->
                            <div class="flex items-center space-x-6">
                                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Change Avatar
                                    </button>
                                    <p class="text-sm text-gray-500 mt-1">JPG, GIF or PNG. 1MB max.</p>
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

                            <!-- Bio Field (if exists in user model) -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="4" 
                                          placeholder="Tell us about yourself..."
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('bio', $user->bio ?? '') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Brief description for your profile. Maximum 500 characters.</p>
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
                    <div x-show="activeTab === 'password'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

                    <!-- Danger Zone Section -->
                    <div x-show="activeTab === 'danger'" class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-red-900">Delete Account</h2>
                            <p class="text-red-600 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        </div>

                        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6" x-data="{ confirmingUserDeletion: false }">
                            @csrf
                            @method('delete')

                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Warning</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>This action cannot be undone. This will permanently delete your account and remove your data from our servers.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" 
                                    @click="confirmingUserDeletion = true"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Account
                            </button>

                            <!-- Confirmation Modal -->
                            <div x-show="confirmingUserDeletion" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                 style="display: none;">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Account</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone.</p>
                                                        <div class="mt-4">
                                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Please enter your password to confirm</label>
                                                            <input id="password" 
                                                                   name="password" 
                                                                   type="password" 
                                                                   placeholder="Password"
                                                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                                            @error('password', 'userDeletion')
                                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                Delete Account
                                            </button>
                                            <button type="button" 
                                                    @click="confirmingUserDeletion = false"
                                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
