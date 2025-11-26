<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S2U - Student to Community</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        input::placeholder {
            color: white;
            opacity: 1;
        }

        select option {
            border-radius: 10px;
            color: #484745;
            background-color: #F0F0F0;

        }
    </style>
</head>

<body class="antialiased">
    <div x-data="{
        mobileMenuOpen: false,
        activeTab: 'students',
        stats: { students: 1250, services: 340, reviews: 890 },
        animateStats: false
    }" x-init="setTimeout(() => animateStats = true, 1000)">

        {{-- Navigation bar --}}
        @include('layouts.navbar')


        <!-- Hero Section -->
        <section class="gradient-bg pt-20 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto"><br><br>
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-8">
                        How can we <span class="text-upsi-gold">help you?</span>
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
                                        <input type="text" name="q" value="{{ $q }}"
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



        <!-- Category Section -->
        <section id="stats" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 style="color: #484745; font-weight:bold; font-size: 20px;">Categories</h2>
                <br>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 text-center">
                    @foreach ($categories as $category)
                        <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300"
                            style="box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;">
                            <div class="text-5 font-bold text-indigo-600 mb-2">
                                {{ $category->name }}
                            </div>
                            <div class="text-gray-600 font-medium">
                                {{ $category->services_count ?? 0 }} Services
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        <!-- Top Services Section -->
        <section class="py-5 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4">

                <h2 style="color: #484745; font-weight:bold; font-size: 20px;">Popular Services</h2><br>

                <!-- Clean Single Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($services->take(6) as $service)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all overflow-hidden flex flex-col">

                            <!-- Header Section -->
                            <div class="p-4 pb-2">
                                <div class="flex items-start justify-between">

                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('students.profile', $service->user) }}">
                                            @if ($service->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                                    class="w-9 h-9 rounded-full object-cover ring-1 ring-gray-300">
                                            @else
                                                <div
                                                    class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ substr($service->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </a>

                                        <div>
                                            <a href="{{ route('students.profile', $service->user) }}"
                                                class="font-medium text-gray-900 text-sm hover:text-indigo-600 transition hover:underline">
                                                {{ Str::limit($service->user->name, 18) }}
                                            </a>

                                            <div class="flex items-center space-x-1 mt-0.5">
                                                @if ($service->user->trust_badge)
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Verified
                                                    </span>
                                                @endif

                                                <!-- Rating -->
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-2.5 h-2.5 {{ $i <= ($service->user->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs text-gray-500 ml-1">
                                                        ({{ $service->user->reviews_count ?? 0 }})
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Availability Badge -->
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                                    {{ $service->user->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                ● {{ $service->user->is_available ? 'Available' : 'Busy' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="px-4 flex-grow">

                                <a href="{{ route('student-services.show', $service) }}"
                                    class="block font-semibold text-upsi-dark text-sm mt-2 hover:text-indigo-600 hover:underline">
                                    {{ Str::limit($service->title, 40) }}
                                </a>

                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                    {{ Str::limit($service->description, 70) }}
                                </p>

                                <!-- Category Tag -->
                                @if ($service->category)
                                    <div class="mt-2">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs">
                                            {{ $service->category->name }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Price -->
                                @if ($service->suggested_price)
                                    <div class="mt-3">
                                        <span class="text-sm font-semibold text-gray-900">
                                            RM {{ number_format($service->suggested_price, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-1">(suggested)</span>
                                    </div>
                                @endif
                            </div> <br>
                            <!-- Action Button - Always at bottom -->
                            <div class="px-4 pb-4 mt-auto">
                                @if (auth()->check() && auth()->user()->role === 'community')
                                    <div class="flex space-x-2">
                                        @if ($service->status === 'available')
                                            <a href="{{ route('chat.request', ['user' => $service->user->id, 'service' => $service->title]) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors shadow-sm">
                                                Contact Provider
                                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @else
                                            <button disabled
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-gray-400 cursor-not-allowed shadow-sm">
                                                Service Unavailable
                                            </button>
                                        @endif
                                        <a href="{{ route('students.profile', $service->user) }}"
                                            class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('students.profile', $service->user) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors group-hover:bg-indigo-700 shadow-sm">
                                        View Profile
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>
        </section>

        <!-- Top Students Section -->
        <section id="stats" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 style="color: #484745; font-weight:bold; font-size: 20px;">Top Helper</h2>
                <br>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 text-center">
                    @foreach ($topStudents as $student)
                        <div class="card rounded-xl shadow-md overflow-hidden mb-4">

                            <div class="p-6 bg-gradient-to-r from-purple-500 to-purple-700 text-white text-center">
                                <div
                                    class="w-16 h-16 mx-auto rounded-full bg-white text-purple-600 flex items-center justify-center text-2xl font-bold">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>

                                <h2 class="mt-3 text-xl font-semibold">{{ $student->name }}</h2>
                                <p class="text-sm opacity-80">Student Service Provider</p>
                            </div>

                            <div class="p-6 text-center">

                                @if ($student->verified ?? true)
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm border rounded-full text-blue-600 border-blue-600 mb-2">
                                        ✅ Verified Student
                                    </span>
                                @endif

                                <div class="mt-2">
                                    <div class="flex justify-center text-gray-400 text-xl">
                                        ★★★★★
                                    </div>
                                    <div class="text-lg font-bold" style="color: #484745;">
                                        {{ number_format($student->average_rating ?? 0, 1) }}
                                    </div>
                                    <div class="text-gray-500 text-sm">
                                        Based on {{ $student->reviews_count ?? 0 }} reviews
                                    </div>
                                </div>

                                <div class="mt-3">
                                    @if ($student->is_available)
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                            ● Available
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm">
                                            ● Not Available
                                        </span>
                                    @endif
                                </div>

                               <br>
                                <a href="{{ route('students.profile', $service->user) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors group-hover:bg-indigo-700 shadow-sm">
                                    View all {{ $student->services_count }} Services
                                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>


        <!-- Footer -->
        @include('layouts.footer')

    </div>
</body>

</html>
