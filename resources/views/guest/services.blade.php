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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FBFBF7;

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
        <br><br><br>

        <div class="max-w-7xl mx-auto px-6 py-10">
            {{-- Page Title --}}
            <h1 class="text-2xl font-bold text-gray-800">Find Student Services</h1>
            <p class="text-gray-600 mb-6">Browse services offered by UPSI students. Request and get things done.</p>
            <br>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- ======================= --}}
                {{--       SIDEBAR           --}}
                {{-- ======================= --}}
                <aside class="lg:col-span-1 bg-white border rounded-xl p-5 shadow-sm h-fit sticky top-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Categories</h2>

                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('guest.services') }}"
                                class="block px-3 py-2 rounded-lg text-sm {{ !$category_id ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                All Categories
                            </a>
                        </li>

                        @foreach ($categories as $cat)
                            <li>
                                <a href="?category_id={{ $cat->id }}"
                                    class="block px-3 py-2 rounded-lg text-sm 
                       {{ $category_id == $cat->id ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </aside>

                {{-- ======================= --}}
                {{--      MAIN CONTENT       --}}
                {{-- ======================= --}}
                <main class="lg:col-span-3">

                    {{-- Top Filters --}}
                    <div class="flex flex-wrap items-center gap-3 mb-5">

                        {{-- Search --}}
                        <form method="GET" class="flex-1">
                            <input type="hidden" name="category_id" value="{{ $category_id }}">

                            <div class="relative">
                                <input type="text" name="q" placeholder="Search services..."
                                    value="{{ request('q') }}"
                                    class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" style="color: rgb(48, 48, 48);">
                                <button class="absolute right-3 top-2.5 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        {{-- Sort --}}
                        <form method="GET">
                            <input type="hidden" name="category_id" value="{{ $category_id }}">
                            <select name="sort" class="border rounded-lg text-sm px-2 py-2"
                                onchange="this.form.submit()">
                                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low →
                                    High</option>
                                <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High →
                                    Low</option>
                            </select>
                        </form>

                    </div>

                    {{-- Title --}}
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-gray-600 text-sm">{{ count($services) }} results found</p>
                    </div>

                    {{-- ======================= --}}
                    {{--     SERVICE LIST        --}}
                    {{-- ======================= --}}
                    <div class="space-y-6">

                        @foreach ($services as $service)
                            <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-md transition">

                                {{-- Top Row --}}
                                <div class="flex justify-between items-start">
                                    <p class="text-xs text-gray-500">
                                        Posted {{ $service->created_at->diffForHumans() }}
                                    </p>

                                    <a href="{{ route('guest.request', $service->id) }}"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                                        Request
                                    </a>
                                </div>

                                {{-- Title --}}
                                <h2 class="text-lg font-semibold text-indigo-700 mt-1 hover:underline">
                                    {{ $service->title }}
                                </h2>

                                {{-- Description --}}
                                <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                                    {{ Str::limit($service->description, 150) }}
                                </p>

                                {{-- Categories & Tags --}}
                             
                                <div class="flex flex-wrap mt-3 gap-2">
                                       @if ($service->category)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium" style="background-color: white; border: 2px solid {{ $service->category->color }}; color:{{ $service->category->color }}">
                                            {{ $service->category->name }}
                                        </span>
                                @endif
                                </div>

                                {{-- Provider --}}
                                <div class="flex items-center gap-3 mt-5">
                                    <img src="{{ $service->student->profile_photo_path
                                        ? asset('storage/' . $service->student->profile_photo_path)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($service->student->name) }}"
                                        class="w-10 h-10 rounded-full object-cover">

                                    <div>
                                        <p class="font-medium text-sm text-gray-900">
                                            {{ $service->student->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">Student Provider</p>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                </main>
            </div>
        </div>
</section>