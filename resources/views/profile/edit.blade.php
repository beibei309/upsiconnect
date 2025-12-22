<x-app-layout>
    <div class="min-h-screen bg-slate-50/50 py-16 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="mb-10 text-center md:text-left">
                <nav class="flex justify-center md:justify-start mb-4">
                    <span class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                        S2U Dashboard
                    </span>
                </nav>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Account Settings</h1>
                <p class="mt-2 text-slate-500 font-medium">Manage your personal identity, security, and preferences on the S2U platform.</p>
            </div>

            <div x-data="{ activeTab: 'profile' }" class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- SIDEBAR NAVIGATION --}}
                <aside class="lg:w-72 flex-shrink-0 w-full sticky top-24">
                    <div class="bg-white p-3 rounded-[2rem] shadow-sm border border-slate-200/60">
                        <nav class="space-y-1">
                            <button @click="activeTab = 'profile'" 
                                :class="activeTab === 'profile' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                                class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl transition-all duration-300 w-full group">
                                <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile Details
                            </button>

                            <button @click="activeTab = 'password'" 
                                :class="activeTab === 'password' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                                class="flex items-center px-5 py-4 text-sm font-bold rounded-2xl transition-all duration-300 w-full group">
                                <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Password & Security
                            </button>
                        </nav>
                        
                        {{-- Quick Help Box --}}
                        <div class="mt-4 p-5 bg-slate-50 rounded-[1.5rem] border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Need Help?</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Contact S2U support if you're having trouble changing your credentials.</p>
                        </div>
                    </div>
                </aside>

                {{-- MAIN CONTENT AREA --}}
                <div class="flex-1 w-full">
                    
                    {{-- TAB 1: PROFILE INFORMATION --}}
                    <div x-show="activeTab === 'profile'" x-cloak 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="opacity-0 translate-y-4" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         class="bg-white shadow-sm rounded-[2.5rem] border border-slate-200/60 overflow-hidden">
                        
                        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight">Profile Information</h3>
                                <p class="text-sm text-slate-500 font-medium">Update your public identity and contact info.</p>
                            </div>
                        </div>
                        
                        <div class="p-8 md:p-10">
                            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-10">
                                @csrf
                                @method('patch')

                                {{-- Photo Upload UI --}}
                                <div class="flex flex-col sm:flex-row items-center gap-8 pb-8 border-b border-slate-50" x-data="{ previewUrl: null }">
                                    <div class="relative group">
                                        <div class="w-28 h-28 md:w-32 md:h-32 rounded-[2rem] overflow-hidden border-4 border-white shadow-2xl bg-slate-100 ring-1 ring-slate-200">
                                            <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                                            
                                            @if(auth()->user()->profile_photo_path)
                                                <img x-show="!previewUrl" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div x-show="!previewUrl" class="w-full h-full flex items-center justify-center bg-indigo-600 text-white text-4xl font-black">
                                                    {{ substr(auth()->user()->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <label for="profile_photo" class="absolute -bottom-2 -right-2 p-3 bg-white rounded-2xl shadow-xl border border-slate-100 cursor-pointer hover:text-indigo-600 text-slate-400 transition-all transform hover:scale-110 active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </label>
                                        <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" @change="previewUrl = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <h4 class="text-lg font-bold text-slate-900">Your Photo</h4>
                                        <p class="text-sm text-slate-400 mt-1 max-w-[200px]">We support JPG, PNG or GIF. Maximum file size is 1MB.</p>
                                        @error('profile_photo') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Input Fields Grid --}}
                                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                        @error('name') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                        @error('email') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+60..."
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                    </div>

                                    @if($user->role === 'student')
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Student ID</label>
                                        <input type="text" value="{{ $user->student_id }}" readonly
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-100 py-3.5 px-4 text-slate-400 font-bold cursor-not-allowed">
                                    </div>

                                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-8">
                                        <div>
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Faculty</label>
                                            <input type="text" name="faculty" value="{{ old('faculty', $user->faculty) }}"
                                                class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Course / Program</label>
                                            <input type="text" name="course" value="{{ old('course', $user->course) }}"
                                                class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-10 flex items-center justify-end border-t border-slate-50 gap-6">
                                    @if (session('status') === 'profile-updated')
                                        <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-bold flex items-center">
                                            <i class="fa-solid fa-circle-check mr-2"></i> Changes Saved
                                        </span>
                                    @endif
                                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 hover:shadow-indigo-100 active:scale-95">
                                        Save Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB 2: PASSWORD UPDATE --}}
                    <div x-show="activeTab === 'password'" x-cloak 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="opacity-0 translate-y-4" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         class="bg-white shadow-sm rounded-[2.5rem] border border-slate-200/60 overflow-hidden">
                        
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Security Credentials</h3>
                            <p class="text-sm text-slate-500 font-medium">Keep your account safe by using a strong password.</p>
                        </div>
                        
                        <div class="p-8 md:p-10">
                            <form method="post" action="{{ route('password.update') }}" class="space-y-8 max-w-2xl">
                                @csrf
                                @method('put')

                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Current Password</label>
                                    <input type="password" name="current_password" 
                                        class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                                    @error('current_password', 'updatePassword') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">New Password</label>
                                        <input type="password" name="password" 
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                                        @error('password', 'updatePassword') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" 
                                            class="block w-full rounded-2xl border-slate-200 bg-slate-50/50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                                    </div>
                                </div>

                                <div class="pt-8 flex items-center justify-end border-t border-slate-50 gap-6">
                                    @if (session('status') === 'password-updated')
                                        <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-bold flex items-center">
                                            <i class="fa-solid fa-shield-check mr-2"></i> Security Updated
                                        </span>
                                    @endif
                                    <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-indigo-600 transition-all shadow-xl shadow-slate-200 hover:shadow-indigo-100 active:scale-95">
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