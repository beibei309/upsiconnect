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
        <section class="relative bg-custom-teal pt-20 pb-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Welcome Header -->
                <br>
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-extrabold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-base text-white mt-2">Discover talented UPSI students and their services</p>
                </div>

                <!-- Search Bar Section -->
                <div class="w-full max-w-5xl">
                    <form action="{{ route('services.index') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search for any service..."
                                class="w-full py-4 pl-5 pr-14 rounded-xl text-lg shadow-lg focus:outline-none text-gray-900 placeholder-gray-400" />

                            <!-- Search Icon -->
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2">
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


                <div class="flex gap-4 mt-4 justify-center ">
                    <a href="{{ route('services.index', ['q' => 'iron baju']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition whitespace-nowrap">
                        iron baju →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'video editing']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition whitespace-nowrap">
                        video editing →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'booth helper']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition whitespace-nowrap">
                        booth helper →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'design poster']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition whitespace-nowrap">
                        design poster →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'pickup parcel']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium flex items-center gap-2 backdrop-blur transition whitespace-nowrap">
                        pickup parcel →
                    </a>
                </div>

            </div>
        </section>



       <section id="categories" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 text-center">
            @foreach ($categories as $category)
                <a href="{{ route('services.index', ['category_id' => $category->id]) }}"
                   class="group bg-white p-4 rounded-xl transform hover:-translate-y-1.5 transition-all duration-300 cursor-pointer"
                   style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid {{ $category->color }};">
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
                </a>
            @endforeach
        </div>
    </div>
</section>



        <!-- Top Services Section -->
        <section class="py-6 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-gray-800 font-bold text-2xl sm:text-2xl mb-4 tracking-tight">
                    Services you may like
                </h2>
                <br>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($services->take(6) as $service)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all flex flex-col overflow-hidden">

                            <!-- Service Image -->
                            <a href="{{ route('student-services.show', $service) }}"
                                class="relative block h-48 bg-gray-100 overflow-hidden group">

                                @if ($service->image_path)
                                    <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                                        alt="{{ $service->title }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                        No Image
                                    </div>
                                @endif

                                <!-- Heart Icon -->
                                <button type="button"
                                    class="absolute top-4 right-6 text-gray-400 hover:text-red-500 transition-colors"
                                    title="Add to favourites">
                                    <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                    </svg>
                                </button>
                            </a>


                            <!-- Content -->
                            <div class="p-4 flex flex-col flex-grow">
                                <!-- Provider Info + Category Badge -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">

                                    <!-- Left: Avatar + Name + Verified -->
                                    <div class="flex items-center space-x-2">
                                        <!-- User Avatar -->
                                        @if ($service->user->profile_photo_path)
                                            <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                                                class="w-9 h-9 rounded-full object-cover ring-1 ring-gray-300">
                                        @else
                                            <div
                                                class="w-9 h-9 bg-gray-300 rounded-full flex items-center justify-center text-white text-xs">
                                                {{ substr($service->user->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <!-- User Name + Verified Badge -->
                                        <div class="flex items-center space-x-1">
                                            <span class="font-medium text-[16px] text-[#2c2b29] font-semibold">
                                                {{ Str::limit($service->user->name, 12) }}
                                            </span>

                                            @if ($service->user->trust_badge)
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right: Service Category Badge -->
                                    @if ($service->category)
                                        <span
                                            class="inline-block px-2 py-0.5 text-xs rounded-full font-semibold flex-shrink-0"
                                            style="border: 1px solid {{ $service->category->color }}; color: {{ $service->category->color }};">
                                            {{ $service->category->name }}
                                        </span>
                                    @endif

                                </div>


                                <!-- Title Below Provider Info -->
                                <a href="{{ route('student-services.show', $service) }}"
                                    class="mt-2 text-gray-900 font-semibold hover:text-indigo-600 line-clamp-2 block"
                                    style="font-size: 16px;">
                                    {{ Str::limit($service->title, 50) }}
                                </a>


                                <!-- Description -->
                                <p class="line-clamp-2 mb-2" style="font-size: 16px; color:#484745;">
                                    {{ Str::limit($service->description, 70) }}
                                </p>

                                <!-- Rating -->
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= ($service->user->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span
                                        class="text-xs text-gray-500 ml-1">({{ $service->user->reviews_count ?? 0 }})</span>
                                </div>


                                <!-- Price -->
                                @if ($service->basic_price)
                                    <div class="text-gray-900 mb-2" style="font-size: 16px;">
                                        From <strong>RM{{ number_format($service->basic_price, 2) }}</strong>
                                    </div>
                                @endif


                                <!-- Action + Share -->
                                <div class="mt-3 flex items-center justify-between space-x-2">

                                    <!-- View Detail Button -->
                                    <a href="{{ route('services.details', $service->id) }}"
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-custom-teal border border-custom-teal text-white hover:bg-white hover:text-custom-teal text-base font-medium rounded shadow transition duration-200">
                                        View Detail
                                    </a>


                                    <!-- Share Modal -->
                                    <div id="shareModal"
                                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-50">
                                        <div
                                            class="bg-white rounded-xl shadow-xl w-80 md:w-96 p-6 transform scale-95 transition-transform duration-300">
                                            <!-- Close Button -->
                                            <button onclick="closeShareModal()"
                                                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>

                                            <!-- Header -->
                                            <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Share This
                                                Service</h3>

                                            <!-- Input + Copy -->
                                            <div class="flex items-center border rounded-lg overflow-hidden mb-4">
                                                <input type="text" id="shareLinkInput"
                                                    class="flex-1 px-3 py-2 text-sm text-gray-700 focus:outline-none"
                                                    readonly>
                                                <button onclick="copyShareLink()"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium transition-colors duration-200">
                                                    Copy
                                                </button>
                                            </div>

                                            <!-- Copy Feedback -->
                                            <p id="copyMessage"
                                                class="text-sm text-green-600 hidden text-center mb-2">Link copied!</p>
                                        </div>
                                    </div>

                                    <!-- Example Share Button -->
                                    <button type="button"
                                        class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full transition"
                                        title="Share Service" onclick="handleShare(this)"
                                        data-url="{{ route('student-services.show', $service) }}"
                                        data-guest="{{ auth()->guest() ? '1' : '0' }}">
                                        <img src="{{ asset('images/share.png') }}" alt="Share" class="w-5 h-5">
                                    </button>

                                    <script>
                                        function handleShare(button) {
                                            const url = button.dataset.url;

                                            const modal = document.getElementById('shareModal');
                                            document.getElementById('shareLinkInput').value = url;

                                            modal.classList.remove('opacity-0', 'pointer-events-none');
                                            modal.querySelector('div').classList.remove('scale-95');
                                            modal.querySelector('div').classList.add('scale-100');
                                        }

                                        function copyShareLink() {
                                            const input = document.getElementById('shareLinkInput');
                                            input.select();
                                            input.setSelectionRange(0, 99999);
                                            document.execCommand("copy");

                                            const msg = document.getElementById('copyMessage');
                                            msg.classList.remove('hidden');
                                            setTimeout(() => msg.classList.add('hidden'), 2000);
                                        }

                                        function closeShareModal() {
                                            const modal = document.getElementById('shareModal');
                                            modal.classList.add('opacity-0', 'pointer-events-none');
                                            modal.querySelector('div').classList.add('scale-95');
                                            modal.querySelector('div').classList.remove('scale-100');
                                        }
                                    </script>

                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('services.index') }}"
                    class="inline-block px-6 py-2 border border-custom-teal text-custom-teal font-medium rounded hover:bg-custom-teal hover:text-white transition-colors duration-300">
                    View More Services →
                </a>
            </div>
        </section>


        <!-- Top Students Section -->
        @php
            $availableHelpers = $topStudents->filter(fn($student) => $student->is_available);
        @endphp

        <section id="stats" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-gray-800 font-bold text-2xl sm:text-2xl mb-4 tracking-tight">
                    Helpers online
                </h2>
                <br>

                <!-- Horizontal Scroll Container -->
                <div class="flex space-x-6 overflow-x-auto pb-4 -mx-4 px-4">
                    @foreach ($availableHelpers as $student)
                        <div
                            class="min-w-[260px] bg-white rounded-2xl shadow-lg overflow-hidden flex-shrink-0 transition-transform hover:-translate-y-1 hover:shadow-2xl">
                            <!-- Header / Profile -->
                            <div
                                class="p-6 bg-gradient-to-r from-teal-400 to-cyan-600 text-white text-center flex flex-col items-center">
                                @if ($student->profile_photo_path)
                                    <img src="{{ asset('storage/' . $student->profile_photo_path) }}"
                                        class="w-32 h-32 rounded-full object-cover ring-2 ring-white mb-4">
                                @else
                                    <div
                                        class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center text-white text-4xl font-bold mb-4">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif

                                <h2 class="text-xl sm:text-2xl font-bold mb-1 truncate">{{ $student->name }}</h2>
                                <p class="text-sm sm:text-base opacity-90 truncate">Student Service Provider</p>
                            </div>

                            <!-- Stats & Info -->
                            <div class="p-4 text-center flex flex-col items-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm mb-3">
                                    ● Online
                                </span>

                                @if ($student->verified ?? true)
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                    </span>
                                @endif

                                <div class="flex flex-col items-center mt-2 mb-3">
                                    <div class="flex text-yellow-400 text-sm mb-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= ($student->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="text-gray-800 font-semibold">
                                        {{ number_format($student->average_rating ?? 0, 1) }}</div>
                                    <div class="text-gray-500 text-xs">({{ $student->reviewsReceived()->count() }}
                                        reviews)
                                    </div>

                                </div>
                                <a href="{{ route('students.profile', $student) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-custom-teal border border-custom-teal text-white hover:bg-white hover:text-custom-teal text-sm font-medium rounded-lg shadow transition duration-200">
                                    View {{ $student->services_count }} Services
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
