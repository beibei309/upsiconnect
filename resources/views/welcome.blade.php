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
        <section class="relative min-h-screen flex items-center overflow-hidden pt-36">

            <!-- Background video -->
            <video autoplay muted loop playsinline
                class="absolute inset-0 w-full h-full object-cover z-0 brightness-20">
                <source src="{{ asset('videos/herobanner.mp4') }}" type="video/mp4">
            </video>


            <!-- Left-side dark overlay using gradient -->
            <div class="absolute inset-0 z-10"
                style="background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
            </div>

            <!-- CONTENT -->
            <div class="relative z-20 w-full max-w-6xl mx-auto px-0.2">
                <h1
                    style="color: white; 
           font-family: 'Poppins', sans-serif; 
           font-size: 50px;
           font-weight: 700; 
           max-width: 48rem;
           line-height: 1.25; 
            margin-bottom: 1.5rem; /* reduce spacing below */
           margin-top: -50px;">
                    UPSI student to community<br>
                    <span style="color: #cd7a3f;">we've got you.</span>
                </h1>

                <!-- SEARCH BAR -->
                <div class="w-full max-w-5xl">
                    <form action="{{ route('search.index') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search for any service..."
                                class="w-full py-4 pl-5 pr-14 rounded-xl text-lg shadow-lg focus:outline-none text-gray-900 placeholder-gray-400" />

                            <!-- Search Icon -->
                            <button class="absolute right-4 top-1/2 -translate-y-1/2">
                                <div class=" w-12 h-12 rounded-xl shadow flex items-center justify-center"
                                    style="background-color: #23221e;">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search idea -->
                <div class="flex gap-5 mt-6 flex-wrap">
                    <a
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md  text-white text-sm font-medium backdrop-blur transition cursor-pointer">
                        iron baju →
                    </a>
                    <a
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md  text-white text-sm font-medium backdrop-blur transition cursor-pointer">
                        video editing →
                    </a>
                    <a
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md  text-white text-sm font-medium backdrop-blur transition cursor-pointer">
                        booth helper →
                    </a>
                    <a
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md  text-white text-sm font-medium backdrop-blur transition cursor-pointer">
                        design poster →
                    </a>
                    <a
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md  text-white text-sm font-medium flex items-center gap-2 backdrop-blur transition cursor-pointer">
                        pickup parcel →
                    </a>
                </div>
            </div>
        </section>


        <section id="categories" class="py-16 bg-gray-50 ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 text-center">

                    @foreach ($categories as $category)
                        <div class="group bg-white p-4 rounded-xl transform hover:-translate-y-1.5 transition-all duration-300 cursor-pointer"
                            style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
                            <!-- Icon -->
                        <div class="mx-auto mb-3 w-12 h-12 flex items-center justify-center rounded-full shadow-sm"
                            style="border: 2px solid {{ $category->color }};">
                                <img src="{{ asset('images/' . $category->image_path) }}" alt="{{ $category->name }}"
                                    class="w-6 h-6">
                            </div>
                            <!-- Name -->
                            <div class="text-sm font-semibold text-gray-900" style="color: {{ $category->color }};">
                                {{ $category->name }}
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
