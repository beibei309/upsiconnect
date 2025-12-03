<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $service->title ?? 'Service Page' }} - S2U</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html,
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        .fiverr-like-shadow {
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
        }

        .seller-badge {
            background: linear-gradient(90deg, #00d28a, #06b6d4);
            color: white;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .object-cover-center {
            object-fit: cover;
            object-position: center;
        }

        .breadcrumb-nav {
            margin-top: 20px;
            /* Adding space above the breadcrumb */
        }

        /* For the hero image (larger size) */
        .hero-image {
            height: 400px;
            /* Adjust height for the hero image */
            object-fit: cover;
            object-position: center;
        }

        /* Sidebar and sticky positioning */
        @media (min-width: 1024px) {
            .sticky-sidebar {
                position: -webkit-sticky;
                position: sticky;
                top: 30px;
            }
        }
    </style>
</head>

<body class="bg-[#F7F7F7] text-gray-800 antialiased">
    @include('layouts.navbar')

    <main class="max-w-7xl mx-auto px-6 py-10" x-data="{ tab: 'about' }">

        <!-- Breadcrumb -->
        <nav class="py-8 px-6 rounded-lg shadow-sm mb-6 breadcrumb-nav" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
                <!-- Home Link -->
                <li class="inline-flex items-center">
                    <a href="{{ route('welcome') }}"
                        class="hover:text-green-600 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>

                <!-- Find Services Link -->
                <li class="inline-flex items-center">
                    <a href="{{ route('services.index') }}"
                        class="hover:text-green-600 hover:underline flex items-center gap-1">
                        Find Services
                    </a>
                    <span class="mx-2 text-gray-400">/</span>
                </li>

                <!-- Service Title -->
                <li class="inline-flex items-center text-gray-500">
                    {{ $service->title ?? 'Service Not Found' }}
                </li>
            </ol>
        </nav>

        <!-- Heading & Seller Information -->
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight">
                {{ $service->title ?? 'I will build your website' }}
            </h1>

            <div class="flex items-center mt-4 space-x-4">
                <div class="w-14 h-14 rounded-full overflow-hidden ring-1 ring-gray-200">
                    <a href="{{ route('students.profile', $service->user) }}" class="group/avatar block">
                        @if ($service->user->profile_photo_path)
                            <img src="{{ asset('images/profile/' . $service->user->profile_photo_path) }}"
                                alt="Profile picture of {{ $service->user->name }}"
                                class="w-8 h-8 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                        @else
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr($service->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </a>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $service->user->name ?? 'Student Name' }}</p>
                    {{-- Trust Badge --}}
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
                    <!-- Availability Status -->
                    <span
                        class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $service->user->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <div
                            class="w-1 h-1 rounded-full {{ $service->user->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-1">
                        </div>
                        {{ $service->user->is_available ? 'Available' : 'Busy' }}
                    </span>

                    <div class="text-sm text-gray-500 flex items-center gap-3">
                        <span class="flex items-center gap-1"><i class="fa-solid fa-star text-yellow-400"></i>
                            {{ $service->rating ?? '4.9' }}</span>
                        <span>•</span>
                        <span>•</span>
                        <span>{{ $service->completed_orders ?? '1.2k' }} orders</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- LEFT: Content and details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hero Image -->
                <div class="rounded-xl overflow-hidden fiverr-like-shadow bg-white mb-6">
                    <img src="{{ $service->image_path ? asset('images/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                        alt="Service image" class="w-full hero-image">
                </div>

                <!-- About this service -->
                <section class="bg-white rounded-xl p-6 fiverr-like-shadow">
                    <h2 class="text-2xl font-semibold mb-3">About This Service</h2>
                    <p class="text-gray-700 leading-relaxed">
                        {!! $service->description ??
                            'I will create a modern, responsive website tailored to your business. Includes design, responsive layout, and one round of revisions.' !!}
                    </p>

                    <!-- Features row -->
                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium">Delivery</span>
                            <span>{{ $service->delivery_time ?? '3 days' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium">Revisions</span>
                            <span>{{ $service->revisions ?? '2' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium">Platform</span>
                            <span>{{ $service->platform ?? 'WordPress / Laravel' }}</span>
                        </div>
                    </div>
                </section>

                <!-- Service Provider Details -->
                <section class="bg-white rounded-xl p-6 fiverr-like-shadow mb-6">
                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 rounded-full overflow-hidden ring-2 ring-gray-200">
                            <img src="{{ $service->seller_image ? asset('images/' . $service->seller_image) : 'https://via.placeholder.com/150' }}"
                                alt="Seller" class="w-full h-full object-cover-center">
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $service->seller_name ?? 'Seller Name' }}</h2>
                            <p class="text-sm text-gray-500">A professional full stack website development master</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-yellow-400">
                                    <i class="fa-solid fa-star"></i> {{ $service->rating ?? '4.9' }}
                                </span>
                                <span class="text-gray-500">| Level 2</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Info -->
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">From: <span
                                class="font-medium">{{ $service->seller_location ?? 'Bangladesh' }}</span></p>
                        <p class="text-sm text-gray-500">Member since: <span
                                class="font-medium">{{ $service->seller_since ?? 'Jan 2024' }}</span></p>
                        <p class="text-sm text-gray-500">Avg. response time: <span
                                class="font-medium">{{ $service->response_time ?? '1 hour' }}</span></p>
                        <p class="text-sm text-gray-500">Languages: <span
                                class="font-medium">{{ $service->languages ?? 'Bengali, English, Hindi, Tamil' }}</span>
                        </p>
                    </div>

                    <!-- Seller Bio -->
                    <div class="mt-6 text-gray-700">
                        <p class="text-sm leading-relaxed">
                            {{ $service->seller_bio ?? 'Welcome to my Fiverr profile! I am a skilled professional website developer with 5 years of experience building beautiful, responsive websites.' }}
                        </p>
                    </div>
                </section>

                <!-- Reviews Section -->
                <!-- Reviews Section -->
                <!-- Reviews Section -->
                <section class="bg-white rounded-xl p-6 fiverr-like-shadow mt-6">
                    <!-- Rating Breakdown -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Reviews</h3>
                            <p class="text-sm text-gray-500">936 reviews for this Gig</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-400">
                                <i class="fa-solid fa-star"></i> 4.9
                            </span>
                            <span class="text-sm text-gray-500">Rating</span>
                        </div>
                    </div>

                    <!-- Review Item -->
                    <div class="flex items-start space-x-4 mb-6">
                        <!-- User Info -->
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <img src="https://via.placeholder.com/150" alt="User Avatar"
                                class="w-full h-full object-cover-center">
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-800">jack_labudzki</span>
                                <span class="text-sm text-gray-500">United States</span>
                            </div>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-yellow-400">
                                    <i class="fa-solid fa-star"></i> 5
                                </span>
                                <span class="text-sm text-gray-500">2 weeks ago</span>
                            </div>
                            <p class="text-gray-700 mt-2">
                                His work was amazing. My site looked professional and clean. I was honestly worried
                                about how I was gonna get a good website going for my business, a website that would
                                actually stand out versus all the other businesses I’m competing against. Let me tell
                                you, it was perfect. I accepted the delivery confidently...
                            </p>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <img src="https://via.placeholder.com/150" alt="Portfolio"
                                class="w-32 h-20 object-cover rounded-lg">
                        </div>
                    </div>

                    <!-- Price & Duration -->
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span class="font-medium">$600 - $800</span>
                        <span class="font-medium">7 days</span>
                    </div>

                    <!-- Seller's Response -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-md">
                        <p class="text-gray-700">
                            <span class="font-semibold">Seller's Response:</span> Thank you so much for the kind words!
                            I’m glad to hear the website exceeded your expectations. Looking forward to working with you
                            again!
                        </p>
                    </div>

                    <!-- Helpful Feedback -->
                    <div class="mt-4 flex items-center space-x-2 text-sm text-gray-500">
                        <span>Helpful?</span>
                        <button class="hover:text-blue-500">Yes</button>
                        <span>|</span>
                        <button class="hover:text-blue-500">No</button>
                    </div>
                </section>


            </div> <!-- END LEFT -->

            <!-- RIGHT: Pricing & Availability -->
            <!-- RIGHT: Pricing & Availability -->
            <aside class="space-y-6 sticky-sidebar">
                <div class="bg-white rounded-xl p-6 fiverr-like-shadow border">
                    <!-- Service Overview -->
                    <div class="  p-3 flex justify-end items-center">
                        <p class="text-sm text-gray-500 mr-2">From</p>
                        <p class="text-4xl font-bold text-gray-800">RM20</p>
                    </div>


                    <!-- Pricing Tab Navigation -->
                    <div class="mt-6">
                        <div class="flex border-b">
                            <!-- Tab Buttons -->
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="basic">
                                Basic
                            </button>
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="standard">
                                Standard
                            </button>
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="premium">
                                Premium
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div id="tab-content" class="mt-4">
                            <!-- Basic Package Tab Content -->
                            <div id="basic" class="tab-content hidden">
                                <p class="font-semibold text-lg">Basic Package</p>
                                <p class="text-sm text-gray-500">1 hour per session</p>
                                <p class="text-sm text-gray-500 mt-2">Perfect for quick help with a specific topic or
                                    assignment. Ideal for last-minute study or revision.</p>
                                <div class="text-xl font-bold mt-3">RM20</div>
                            </div>

                            <!-- Standard Package Tab Content -->
                            <div id="standard" class="tab-content hidden">
                                <p class="font-semibold text-lg">Standard Package</p>
                                <p class="text-sm text-gray-500">1 hour per session, weekly</p>
                                <p class="text-sm text-gray-500 mt-2">Weekly tutoring sessions for ongoing support.
                                    Ideal for students who need consistent help with their studies.</p>
                                <div class="text-xl font-bold mt-3">RM70</div>
                            </div>

                            <!-- Premium Package Tab Content -->
                            <div id="premium" class="tab-content hidden">
                                <p class="font-semibold text-lg">Premium Package</p>
                                <p class="text-sm text-gray-500">2 hours per session, intensive exam preparation</p>
                                <p class="text-sm text-gray-500 mt-2">Comprehensive exam prep with practice tests and
                                    study materials. Ideal for students gearing up for exams.</p>
                                <div class="text-xl font-bold mt-3">RM120</div>
                            </div>
                        </div>
                    </div>

                    <!-- Availability Section -->
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">When do you need us?</p>
                        <p class="text-sm text-gray-400 mt-2">Available slots: Select a date to check availability</p>

                        <!-- Date Picker with Available Days -->
                        <div class="mt-3">
                            <input type="date" id="calendar" class="w-full p-2 border rounded-lg text-sm" />
                        </div>
                    </div>

                    <!-- Display Selected Date and Available Slots -->
                    <div id="availability-status" class="mt-4 text-gray-600">
                        <!-- Initially empty, will display the selected date's availability -->
                    </div>

                    <!-- Request Services Button -->
                    <!-- Request Services Button -->
                    <button
                        class="mt-6 w-full bg-gradient-to-r from-[#1DBF73] to-[#17a65a] hover:from-[#17a65a] hover:to-[#1DBF73] text-white font-semibold py-3 rounded-lg shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                        Request Services
                    </button>

                    <!-- Chat Button -->
                    @auth
                        @if ($viewer->id !== $provider->id && $viewer->isCommunity() && $provider->isStudent())
                            <a href="{{ route('chat.request', ['user' => $provider->id]) }}">
                                <button
                                    class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center gap-2
                {{ !$provider->is_available ? 'cursor-not-allowed opacity-50' : '' }}"
                                    {{ !$provider->is_available ? 'disabled' : '' }}>
                                    <i class="fas fa-comment-alt text-lg"></i>
                                    {{ $provider->is_available ? 'Contact me' : 'Currently Unavailable' }}
                                </button>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}">
                            <button
                                class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center gap-2">
                                <i class="fas fa-comment-alt text-lg"></i> Login to Contact
                            </button>
                        </a>
                    @endauth
                    <br>
                    <hr class="border-t-2 border-gray-300 my-4">

                    <!-- Share and Favorite Icons -->
                    <div class="mt-4 flex justify-center gap-6">
                        <!-- Share Icon -->
                        <button class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
                            <i class="fas fa-share-alt text-2xl"></i>
                            <p class="text-xs mt-1">Share</p>
                        </button>

                        <!-- Favorite Icon -->
                        <button class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
                            <i class="fas fa-heart text-2xl"></i>
                            <p class="text-xs mt-1">Favorite</p>
                        </button>
                    </div>



                </div>
            </aside>

            <script>
                // JavaScript for Tab Switching
                const tabs = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const selectedTab = tab.getAttribute('data-tab');

                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Show the selected tab content
                        document.getElementById(selectedTab).classList.remove('hidden');

                        // Change tab styles
                        tabs.forEach(tab => {
                            tab.classList.remove('border-b-2', 'border-green-500');
                            tab.classList.add('text-gray-600');
                        });
                        tab.classList.add('border-b-2', 'border-green-500');
                        tab.classList.remove('text-gray-600');
                    });
                });

                // Default to showing the Basic package
                document.querySelector('[data-tab="basic"]').click();
            </script>


        </div> <!-- grid -->
    </main>

    @include('layouts.footer')

</body>

</html>
