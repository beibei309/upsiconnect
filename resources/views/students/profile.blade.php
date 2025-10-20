<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Student Profile Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                        <!-- Profile Header -->
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-8 text-center">
                            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-indigo-600 font-bold text-2xl mx-auto mb-4">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                            <p class="text-indigo-100 text-sm mt-1">Student Service Provider</p>
                        </div>

                        <!-- Profile Info -->
                        <div class="p-6 space-y-6">
                            <!-- Trust Badge -->
                            @if($user->trust_badge)
                                <div class="flex items-center justify-center">
                                    <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verified Student
                                    </span>
                                </div>
                            @endif

                            <!-- Rating -->
                            <div class="text-center">
                                <div class="flex items-center justify-center space-x-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= ($user->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($user->average_rating ?? 0, 1) }}</p>
                                <p class="text-sm text-gray-500">Based on {{ $user->reviews_count ?? 0 }} {{ Str::plural('review', $user->reviews_count ?? 0) }}</p>
                            </div>

                            <!-- Availability Status -->
                            <div class="text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $user->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $user->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                                    {{ $user->is_available ? 'Available for new projects' : 'Currently unavailable' }}
                                </span>
                            </div>

                            <!-- Contact Actions -->
                            <div class="space-y-3">
                                @auth
                                    @if(auth()->id() !== $user->id)
                                        <button 
                                            {{ !$user->is_available ? 'disabled' : '' }}
                                            class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-lg text-sm font-medium text-white {{ $user->is_available ? 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500' : 'bg-gray-400 cursor-not-allowed' }} focus:outline-none focus:ring-2 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l2.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                            </svg>
                                            {{ $user->is_available ? 'Send Message' : 'Currently Unavailable' }}
                                        </button>
                                        
                                        <button class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            Save to Favorites
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                        Login to Contact
                                    </a>
                                @endauth
                            </div>

                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $user->services_count ?? 0 }}</p>
                                    <p class="text-sm text-gray-500">Services</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $user->completed_projects ?? 0 }}</p>
                                    <p class="text-sm text-gray-500">Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- About Section -->
                    @if($user->bio)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">About {{ $user->name }}</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                        </div>
                    @endif

                    <!-- Services Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Services Offered</h2>
                            <span class="text-sm text-gray-500">{{ $user->services->count() }} {{ Str::plural('service', $user->services->count()) }}</span>
                        </div>

                        @if($user->services->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($user->services as $service)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                                        <div class="flex items-start justify-between mb-3">
                                            <h3 class="font-medium text-gray-900">{{ $service->title }}</h3>
                                            @if($service->price)
                                                <span class="text-lg font-semibold text-indigo-600">${{ number_format($service->price, 2) }}</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $service->description }}</p>
                                        
                                        @if($service->category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $service->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No services yet</h3>
                                <p class="mt-2 text-gray-500">This student hasn't added any services yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Reviews & Feedback</h2>
                            <span class="text-sm text-gray-500">{{ $user->reviews_count ?? 0 }} {{ Str::plural('review', $user->reviews_count ?? 0) }}</span>
                        </div>

                        @if(($user->reviews_count ?? 0) > 0)
                            <!-- Reviews would be loaded here from the backend -->
                            <div class="space-y-6">
                                <!-- Sample review structure -->
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            J
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-gray-900">John Doe</h4>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= 5 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-gray-600 mb-2">Excellent work on my programming assignment. Very professional and delivered on time.</p>
                                            <p class="text-sm text-gray-500">2 weeks ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Load More Reviews -->
                            <div class="mt-6 text-center">
                                <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                    Load More Reviews
                                </button>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l2.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                                <p class="mt-2 text-gray-500">Be the first to leave a review for {{ $user->name }}!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Skills & Expertise -->
                    @if($user->skills)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Skills & Expertise</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $user->skills) as $skill)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>