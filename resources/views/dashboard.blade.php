<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
                @if(Auth::user()->role === 'student')
                    <p class="text-gray-600 mt-2">Manage your services and connect with the community</p>
                @elseif(Auth::user()->role === 'community')
                    <p class="text-gray-600 mt-2">Discover talented UPSI students and their services</p>
                @else
                    <p class="text-gray-600 mt-2">Manage your profile and platform activities</p>
                @endif
            </div>

            @if(Auth::user()->role === 'student')
                <!-- STUDENT DASHBOARD -->
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Availability Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Availability Status</h3>
                                <p class="text-sm text-gray-600 mt-1">Toggle your availability for new requests</p>
                            </div>
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           id="availability-toggle"
                                           class="sr-only peer" 
                                           {{ Auth::user()->is_available ? 'checked' : '' }}
                                           x-data="{ 
                                               toggle() {
                                                   this.updating = true;
                                                   fetch('/availability/toggle', {
                                                       method: 'POST',
                                                       headers: {
                                                           'Content-Type': 'application/json',
                                                           'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                                       }
                                                   })
                                                   .then(response => response.json())
                                   .then(data => {
                                       this.updating = false;
                                       if (data.is_available !== undefined) {
                                           // Update status text and reload to show updated status
                                           window.location.reload();
                                       } else if (data.error) {
                                           alert(data.error);
                                           // Revert the toggle
                                           this.$el.checked = !this.$el.checked;
                                       }
                                   })
                                                   .catch(error => {
                                                       this.updating = false;
                                                       console.error('Error:', error);
                                                   });
                                               }
                                           }"
                                           @change="toggle()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ Auth::user()->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-2 h-2 rounded-full {{ Auth::user()->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                                {{ Auth::user()->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>

                    <!-- My Services -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">My Services</h3>
                                <p class="text-sm text-gray-600 mt-1">Services you're offering</p>
                            </div>
                            <div class="text-2xl font-bold text-indigo-600">{{ Auth::user()->studentServices()->count() }}</div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('services.manage') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                Manage Services →
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                                <p class="text-sm text-gray-600 mt-1">Your latest interactions</p>
                            </div>
                            <div class="text-2xl font-bold text-green-600">{{ Auth::user()->chatRequestsReceived()->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Pending chat requests</p>
                        </div>
                    </div>
                </div>

                <!-- Student Quick Actions with Add Service -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('services.create') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Add Service</h3>
                                <p class="text-sm text-gray-500">Create a new service</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('services.manage') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Manage Services</h3>
                                <p class="text-sm text-gray-500">Edit existing services</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('chat.index.demo') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Messages</h3>
                                <p class="text-sm text-gray-500">Chat with community</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">My Profile</h3>
                                <p class="text-sm text-gray-500">Update information</p>
                            </div>
                        </div>
                    </a>
                </div>

            @elseif(Auth::user()->role === 'community')
                <!-- COMMUNITY DASHBOARD -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Verification Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Verification Status</h3>
                                <p class="text-sm text-gray-600 mt-1">Your account verification</p>
                            </div>
                            <div class="text-2xl">
                                @if(Auth::user()->isVerifiedPublic())
                                    <span class="text-green-500">✓</span>
                                @else
                                    <span class="text-yellow-500">⏳</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ Auth::user()->isVerifiedPublic() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ Auth::user()->trust_badge }}
                            </span>
                        </div>
                    </div>

                    <!-- Available Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Available Students</h3>
                                <p class="text-sm text-gray-600 mt-1">Students ready to help</p>
                            </div>
                            <div class="text-2xl font-bold text-indigo-600">{{ \App\Models\User::where('role', 'student')->where('is_available', true)->count() }}</div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('search.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                Browse Students →
                            </a>
                        </div>
                    </div>

                    <!-- My Requests -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">My Requests</h3>
                                <p class="text-sm text-gray-600 mt-1">Chat requests sent</p>
                            </div>
                            <div class="text-2xl font-bold text-green-600">{{ Auth::user()->chatRequestsSent()->count() }}</div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Total requests sent</p>
                        </div>
                    </div>
                </div>

                <!-- Community Quick Actions with Apply for Services -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('search.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Browse Services</h3>
                                <p class="text-sm text-gray-500">Find student services</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('services.apply') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Apply for Services</h3>
                                <p class="text-sm text-gray-500">Request student help</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('chat.index.demo') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">Messages</h3>
                                <p class="text-sm text-gray-500">Chat with students</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">My Profile</h3>
                                <p class="text-sm text-gray-500">Update information</p>
                            </div>
                        </div>
                    </a>
                </div>

                @if(!Auth::user()->isVerifiedPublic())
                <!-- Verification Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Account Verification Required</h3>
                            <p class="text-sm text-yellow-700 mt-1">Complete your account verification to access all features and connect with students.</p>
                            <div class="mt-3">
                                <a href="{{ route('onboarding.community.verify') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Complete Verification
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Featured Services Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Featured Services</h2>
                        <a href="{{ route('search.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                            View All →
                        </a>
                    </div>

                    @php
                        $featuredServices = \App\Models\StudentService::with(['student', 'category'])
                            ->where('is_active', true)
                            ->whereHas('student', function($query) {
                                $query->where('is_available', true)->where('role', 'student');
                            })
                            ->inRandomOrder()
                            ->limit(6)
                            ->get();
                    @endphp

                    @if($featuredServices->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($featuredServices as $service)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900 mb-1">{{ $service->title }}</h3>
                                            <p class="text-sm text-gray-600 mb-2">by {{ $service->student->name }}</p>
                                            @if($service->category)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $service->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($service->suggested_price)
                                            <div class="text-right">
                                                <span class="text-lg font-semibold text-green-600">RM {{ number_format($service->suggested_price, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($service->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($service->description, 100) }}</p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= ($service->student->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                                <span class="text-sm text-gray-500 ml-1">({{ $service->student->reviewsReceived->count() }})</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('students.profile', $service->student) }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                            View Profile
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No services available</h3>
                            <p class="mt-1 text-sm text-gray-500">Check back later for new services from students.</p>
                        </div>
                    @endif
                </div>

            @else
                <!-- ADMIN/STAFF DASHBOARD -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Verifications</h3>
                        <div class="text-2xl font-bold text-yellow-600 mt-2">
                            {{ \App\Models\User::where('verification_status', 'pending')->count() }}
                        </div>
                        <a href="{{ route('admin.verifications.page') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium mt-2 inline-block">
                            Review →
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Open Reports</h3>
                        <div class="text-2xl font-bold text-red-600 mt-2">
                            {{ \App\Models\Report::where('status', 'open')->count() }}
                        </div>
                        <a href="{{ route('admin.reports.page') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium mt-2 inline-block">
                            Review →
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Total Users</h3>
                        <div class="text-2xl font-bold text-green-600 mt-2">
                            {{ \App\Models\User::count() }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity Section (Common for all roles) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
                </div>

                <div class="space-y-4">
                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">Profile updated</span>
                            </p>
                            <p class="text-sm text-gray-500">You updated your profile information</p>
                            <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'student')
                    <!-- Activity Item for Students -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">New chat request</span>
                            </p>
                            <p class="text-sm text-gray-500">Someone wants to connect with you</p>
                            <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">New review received</span>
                            </p>
                            <p class="text-sm text-gray-500">You received a 5-star review for your service</p>
                            <p class="text-xs text-gray-400 mt-1">3 days ago</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- View All Activity -->
                <div class="mt-6 text-center">
                    <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                        View all activity →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
