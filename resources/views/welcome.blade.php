<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UpsiConnect') }} - Connect, Learn, Grow</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="antialiased">
    <div x-data="{ 
        mobileMenuOpen: false, 
        activeTab: 'students',
        stats: { students: 1250, services: 340, reviews: 890 },
        animateStats: false
    }" x-init="setTimeout(() => animateStats = true, 1000)">
        
        <!-- Navigation -->
        @include('layouts.navbar')


        <!-- Hero Section -->
        <section class="gradient-bg pt-20 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Connect. Learn. <span class="text-upsi-gold">Grow Together.</span>
                    </h1>

                    <!-- Search and Filters -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8" x-data="{ showFilters: false }" style="color:#484745;">
                        <!-- Search Bar -->
                        <form method="GET" action="{{ route('search.index') }}" class="space-y-4" >
                            <div class="flex flex-col lg:flex-row gap-4">
                                <div class="flex-1">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="q" value="{{ $q ?? '' }}"
                                            placeholder="Search for services, skills, or student names..."
                                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="showFilters = !showFilters"
                                        class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
                                            </path>
                                        </svg>
                                        Filters
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Search
                                    </button>
                                </div>
                            </div>

                            <!-- Advanced Filters -->
                            <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">

                                <!-- Category Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-upsi-dark mb-2">Category</label>
                                    <select name="category_id"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Rating Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-upsi-dark mb-2">Minimum Rating</label>
                                    <select name="min_rating"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Any Rating</option>
                                        <option value="4" {{ $min_rating == '4' ? 'selected' : '' }}>4+ Stars
                                        </option>
                                        <option value="3" {{ $min_rating == '3' ? 'selected' : '' }}>3+ Stars
                                        </option>
                                        <option value="2" {{ $min_rating == '2' ? 'selected' : '' }}>2+ Stars
                                        </option>
                                    </select>
                                </div>

                                <!-- Availability Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-upsi-dark mb-2">Availability</label>
                                    <select name="available"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="1" {{ $available == '1' ? 'selected' : '' }}>Available Now
                                        </option>
                                        <option value="">All Students</option>
                                        <option value="0" {{ $available == '0' ? 'selected' : '' }}>Unavailable
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <!-- Active Filters -->
                        @if ($q || $category_id || $min_rating || ($available !== null && $available != '1'))
                            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200">
                                <span class="text-sm text-upsi-text-primary">Active filters:</span>

                                @if ($q)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Search: "{{ $q }}"
                                        <a href="{{ route('search.index', array_filter(['category_id' => $category_id, 'min_rating' => $min_rating, 'available' => $available != '1' ? $available : null])) }}"
                                            class="ml-1 text-indigo-600 hover:text-indigo-500">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </span>
                                @endif

                                @if ($category_id)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Category
                                        <a href="{{ route('search.index', array_filter(['q' => $q, 'min_rating' => $min_rating, 'available' => $available != '1' ? $available : null])) }}"
                                            class="ml-1 text-green-600 hover:text-green-500">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </span>
                                @endif

                                @if ($min_rating)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $min_rating }}+ Stars
                                        <a href="{{ route('search.index', array_filter(['q' => $q, 'category_id' => $category_id, 'available' => $available != '1' ? $available : null])) }}"
                                            class="ml-1 text-yellow-600 hover:text-yellow-500">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </span>
                                @endif

                                @if ($available !== null && $available != '1')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $available ? 'Available' : 'Unavailable' }}
                                        <a href="{{ route('search.index', array_filter(['q' => $q, 'category_id' => $category_id, 'min_rating' => $min_rating])) }}"
                                            class="ml-1 text-purple-600 hover:text-purple-500">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </span>
                                @endif

                                <a href="{{ route('search.index') }}"
                                    class="text-sm text-gray-500 hover:text-gray-700">Clear all</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.students : 0"></div>
                        <div class="text-gray-600 font-medium">Active Students</div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.services : 0"></div>
                        <div class="text-gray-600 font-medium">Services Available</div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.reviews : 0"></div>
                        <div class="text-gray-600 font-medium">Positive Reviews</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-upsi-dark mb-4">Why Choose UpsiConnect?</h2>
                    <p class="text-xl text-upsi-text-primary max-w-2xl mx-auto">
                        Built by students, for students. Our platform makes it easy to find help and offer your skills.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-upsi-dark mb-4">Trusted Community</h3>
                        <p class="text-upsi-text-primary">All users are verified UPSI students. Build trust through our rating and review system.</p>
                    </div>
                    
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-upsi-dark mb-4">Instant Connections</h3>
                        <p class="text-upsi-text-primary">Find help quickly with our smart search and instant messaging system.</p>
                    </div>
                    
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-upsi-dark mb-4">Fair Pricing</h3>
                        <p class="text-upsi-text-primary">Student-friendly rates with transparent pricing. No hidden fees or commissions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How UpsiConnect Works</h2>
                    <p class="text-xl text-gray-600">Simple steps to connect with your peers</p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="flex justify-center mb-12">
                    <div class="bg-white p-1 rounded-lg shadow-sm">
                        <button @click="activeTab = 'students'" 
                                :class="activeTab === 'students' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'"
                                class="px-6 py-3 rounded-md font-medium transition">
                            For Students Seeking Help
                        </button>
                        <button @click="activeTab = 'providers'" 
                                :class="activeTab === 'providers' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'"
                                class="px-6 py-3 rounded-md font-medium transition">
                            For Service Providers
                        </button>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div x-show="activeTab === 'students'" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h3 class="text-lg font-semibold mb-2">Search & Browse</h3>
                        <p class="text-gray-600">Use our smart filters to find exactly what you need - tutoring, design, coding, and more.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h3 class="text-lg font-semibold mb-2">Connect & Chat</h3>
                        <p class="text-gray-600">Send a chat request to discuss your needs, timeline, and pricing with the service provider.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h3 class="text-lg font-semibold mb-2">Learn & Review</h3>
                        <p class="text-gray-600">Get the help you need and leave a review to help other students make informed decisions.</p>
                    </div>
                </div>
                
                <div x-show="activeTab === 'providers'" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h3 class="text-lg font-semibold mb-2">Create Your Profile</h3>
                        <p class="text-gray-600">Set up your services, showcase your skills, and set your availability status.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h3 class="text-lg font-semibold mb-2">Receive Requests</h3>
                        <p class="text-gray-600">Get notified when students are interested in your services and start conversations.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h3 class="text-lg font-semibold mb-2">Earn & Grow</h3>
                        <p class="text-gray-600">Help fellow students while earning money and building your reputation in the community.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 gradient-bg">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Join the Community?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Whether you need help with your studies or want to share your skills, UpsiConnect is here for you.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105 inline-block">
                        Go to Your Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105 inline-block">
                        Get Started Today
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        @include('layouts.footer')
        
    </div>
</body>
</html>
