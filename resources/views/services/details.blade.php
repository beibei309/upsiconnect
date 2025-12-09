headers: {
"Content-Type": "application/json",
"X-CSRF-TOKEN": "{{ csrf_token() }}"
},

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .unavailable-date {
            background-color: #ffcccc !important;
            color: #b30000 !important;
            border-radius: 8px;
            font-weight: 600;
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

        .tab-button {
            transition: all 0.3s ease-in-out;
            /* smooth background, color, and scale changes */
            border-radius: 0.5rem;
            /* slightly rounded */
        }

        .tab-button.active {
            transform: scale(1.05);
            /* slight enlargement on active */
        }
    </style>
</head>

<body class="bg-[#F7F7F7] text-gray-800 antialiased">
    @include('layouts.navbar')

    <main class="max-w-7xl mx-auto px-6 py-10" x-data="{ tab: 'about' }">
        <br><br>

        <nav class="py-6 px-6 rounded-lg bg-[#F7F7F7] mb-6 breadcrumb-nav" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
                <!-- Home Link -->
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-green-600 hover:underline flex items-center gap-1">
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
                <li class="inline-flex items-center text-gray-800">
                    {{ $service->title ?? 'Service Not Found' }}
                </li>
            </ol>
        </nav>


        <!-- Heading & Seller Information -->
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight">
                {{ $service->title ?? 'Services title' }}
            </h1>

            <div class="flex items-center mt-4 space-x-4">
                <div class="w-14 h-14 rounded-full overflow-hidden ring-1 ring-gray-200">
                    @php
                        $isGuest = auth()->guest();
                    @endphp

                    <a href="{{ $isGuest ? route('login') : route('students.profile', $service->user) }}"
                        class="group/avatar block relative w-16 h-16 rounded-full overflow-hidden">
                        <!-- size container -->

                        <!-- Profile image / initial -->
                        @if ($service->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                alt="Profile picture of {{ $service->user->name }}"
                                class="absolute inset-0 w-full h-full object-cover object-center
                    transition duration-300
                    {{ $isGuest ? 'blur-sm group-hover:blur-none' : '' }}">
                        @else
                            <div
                                class="absolute inset-0 flex items-center justify-center text-white font-semibold
                    text-xl bg-gradient-to-br from-indigo-500 to-purple-600
                    transition duration-300 {{ $isGuest ? 'blur-sm group-hover:blur-none' : '' }}">
                                {{ strtoupper(substr($service->user->name, 0, 1)) }}
                            </div>
                        @endif

                        <!-- Guest overlay -->
                        @if ($isGuest)
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-25
                    text-xs text-white font-semibold opacity-0 group-hover:opacity-100 transition duration-300 rounded-full">
                                Sign in to view
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
                        <!-- Rating -->
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            {{ $service->rating }}
                        </span>

                        <span>•</span>

                        <!-- Optional separator -->
                        <span>•</span>

                        <!-- Completed orders -->
                        <span>{{ $service->completed_orders }} orders</span>
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
                    <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                        alt="Service image" class="w-full hero-image">
                </div>

                <!-- About this service -->
                <section class="bg-white rounded-xl p-6 fiverr-like-shadow">
                    <h2 class="text-2xl font-semibold mb-3">About This Service</h2>
                    <p class="text-gray-700 leading-relaxed">
                        {!! $service->description ??
                            'I will create a modern, responsive website tailored to your business. Includes design, responsive layout, and one round of revisions.' !!}
                    </p>

                </section>

                @php
                    $isGuest = !auth()->check(); // true if user is not logged in
                @endphp

                <!-- Service Provider Details -->
                <section class="bg-white rounded-xl p-6 fiverr-like-shadow mb-6 relative">

                    @if ($isGuest)
                        <!-- Overlay text (sharp, not blurred) -->
                        <a href="{{ route('login') }}"
                            class="absolute inset-0 flex items-center justify-center rounded-xl z-20 pointer-events-auto">
                            <span
                                class="text-gray-700 font-semibold text-lg uppercase tracking-wide bg-white/90 px-4 py-2 rounded-md shadow hover:bg-white transition">
                                Sign in to view
                            </span>
                        </a>
                        <!-- Card content blurred -->
                        <div class="filter blur-sm pointer-events-none select-none">
                        @else
                            <div>
                    @endif

                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 rounded-full overflow-hidden ring-2 ring-gray-200">
                            @if ($service->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                    alt="Profile picture of {{ $service->user->name }}"
                                    class="w-full h-full object-cover object-top rounded-full ring-1 ring-gray-200">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $service->user->name ?? 'Student helper Name' }}</h2>
                            <p class="text-sm text-gray-500">{{ $service->user->email ?? 'email' }}</p>
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
                        <p class="text-sm text-gray-500">From <span
                                class="font-medium">{{ $service->user->faculty ?? 'Faculty' }}</span></p>
                        <p class="text-sm text-gray-500">Member since: <span
                                class="font-medium">{{ $service->created_at ? $service->created_at->format('F Y') : 'New member' }}</span>
                        </p>
                    </div>

                    <!-- Seller Bio -->
                    <div class="mt-6 text-gray-700">
                        <p class="text-sm leading-relaxed">{{ $service->user->bio ?? '' }}</p>
                    </div>

            </div> <!-- Close blurred wrapper -->
            </section>


            <!-- Reviews Section -->
            <section class="bg-white rounded-xl p-6 fiverr-like-shadow mt-6">
                <!-- Rating Breakdown -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Reviews</h3>
                        <p class="text-sm text-gray-500">
                            {{ $service->user->reviewsReceived()->count() }} reviews for this Gig
                        </p>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-1">
                        <span class="text-yellow-400 flex items-center gap-1">
                            <i class="fa-solid fa-star"></i>
                            {{ round($service->user->reviewsReceived()->avg('rating'), 1) ?? 0 }}
                        </span>
                        <span class="text-sm text-gray-500">Rating</span>
                    </div>
                </div>

                <!-- Review Item -->
                @foreach ($service->user->reviewsReceived as $review)
                    <div class="flex items-start space-x-4 mb-6">
                        <!-- Reviewer Avatar -->
                        @php
                            $isGuest = !auth()->check();
                        @endphp

                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            @if ($service->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                    alt="Reviewer Avatar"
                                    class="w-full h-full object-cover {{ $isGuest ? 'blur-sm' : '' }}">
                            @endif
                        </div>


                        <!-- Review Content -->
                        <div class="flex flex-col flex-1">
                            <!-- Reviewer Name and Location -->
                            @php
                                $isGuest = !auth()->check();
                                $reviewerName = $review->reviewer->name ?? 'Anonymous';
                            @endphp

                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-800">
                                    @if ($isGuest)
                                        {{ Str::limit($reviewerName, 1, '*') . str_repeat('*', max(0, strlen($reviewerName) - 1)) }}
                                    @else
                                        {{ $reviewerName }}
                                    @endif
                                </span>

                                @if (isset($review->reviewer->country))
                                    <span class="text-sm text-gray-500">{{ $review->reviewer->country }}</span>
                                @endif
                            </div>


                            <!-- Rating and Time -->
                            <div class="flex items-center gap-1 mt-1">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endfor
                                </div>
                                <span
                                    class="text-sm text-gray-500 ml-1">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Review Text -->
                            <p class="text-gray-700 mt-2 line-clamp-3">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <!-- Optional Portfolio Image -->
                        @if ($review->portfolio_image_path)
                            <div class="flex-shrink-0 ml-4">
                                <img src="{{ asset('storage/' . $review->portfolio_image_path) }}" alt="Portfolio"
                                    class="w-32 h-20 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Price & Duration -->
                <div class="flex justify-between text-sm text-gray-500 mb-4">
                    <span class="font-medium">RM{{ number_format($service->min_price, 2) }} -
                        RM{{ number_format($service->max_price, 2) }}</span>
                    <span class="font-medium">{{ round($service->avg_days) }} days</span>

                </div>


                <!-- Seller's Response -->
                {{-- <div class="mt-4 p-4 bg-gray-50 rounded-md">
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
                    </div> --}}
            </section>


        </div> <!-- END LEFT -->

        <!-- RIGHT: Pricing & Availability -->
        <aside class="space-y-6 sticky-sidebar">
            <div class="bg-white rounded-xl p-6 fiverr-like-shadow border">
                <!-- Service Overview -->
                <div class="p-3 flex justify-end items-center">
                    <p class="text-sm text-gray-800 mr-2">From</p>
                    <p class="text-4xl font-bold" style="color:#367588;">RM{{ $service->basic_price ?? '20' }}</p>
                    <!-- Default price for fallback -->
                </div>

                <!-- Pricing Tab Navigation -->
                <div class="mt-6">
                    <div class="flex border-b">
                        <!-- Tab Buttons -->
                        @if ($service->basic_price)
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="basic">
                                Basic
                            </button>
                        @endif

                        @if ($service->standard_price)
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="standard">
                                Standard
                            </button>
                        @endif

                        @if ($service->premium_price)
                            <button
                                class="tab-button px-4 py-2 text-sm font-semibold w-full text-center focus:outline-none"
                                data-tab="premium">
                                Premium
                            </button>
                        @endif
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-content" class="mt-4">
                        <!-- Basic Package Tab Content -->
                        @if ($service->basic_price)
                            <div id="basic" data-tab="basic" class="tab-content hidden">
                                <p class="font-bold text-lg" style="color: #367588">Basic Package</p>
                                <p class="text-sm text-gray-800">{{ $service->basic_duration }} hour per session
                                </p>
                                <p class="text-sm text-gray-800 mt-2">{{ $service->basic_description }}</p>
                            </div>
                        @endif

                        <!-- Standard Package Tab Content -->
                        @if ($service->standard_price)
                            <div id="standard" data-tab="standard" class="tab-content hidden">
                                <p class="font-bold text-lg" style="color: #F0B13B">Standard Package</p>
                                <p class="text-sm text-gray-800">{{ $service->standard_duration }} hour per
                                    session, {{ $service->standard_frequency }}</p>
                                <p class="text-sm text-gray-800 mt-2">{{ $service->standard_description }}</p>
                            </div>
                        @endif

                        <!-- Premium Package Tab Content -->
                        @if ($service->premium_price)
                            <div id="premium" data-tab="premium"class="tab-content hidden">
                                <p class="font-bold text-lg" style="color: #E7180B">Premium Package</p>
                                <p class="text-sm text-gray-800">{{ $service->premium_duration }} hours per
                                    session, intensive exam preparation</p>
                                <p class="text-sm text-gray-800 mt-2">{{ $service->premium_description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <!-- Availability Section -->
                <div class="mt-6">
                    <p class="text-sm font-bold text-gray-900">When do you need us?</p>
                    <p class="text-sm text-gray-600 mt-2">Available slots: Select a date to check availability</p>

                    <!-- Date Picker -->
                    <div class="mt-3">
                        <input type="text" id="calendar"
                            class="w-full p-2 border border-gray-600 rounded-lg text-sm"
                            placeholder="Please select a date" />
                    </div>

                    <!-- Availability Status -->
                    <div id="availability-status" class="mt-4 text-gray-600"></div>
                </div>

                <!-- Flatpickr -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


                <div class="mt-4" id="availability-status"></div>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        let unavailableDates = @json($service->unavailable_dates ? json_decode($service->unavailable_dates) : []);
                        const statusDiv = document.getElementById("availability-status");

                        flatpickr("#calendar", {
                            dateFormat: "Y-m-d",
                            minDate: "today",
                            disable: unavailableDates,
                            onDayCreate: function(dObj, dStr, fp, dayElem) {
                                let date = dayElem.dateObj.toISOString().split("T")[0];
                                if (unavailableDates.includes(date)) {
                                    dayElem.classList.add("bg-red-600", "text-white", "font-bold");
                                    dayElem.style.borderRadius = "0.35rem";
                                }
                            },
                            onChange: function(selectedDates, dateStr) {
                                if (!dateStr) {
                                    statusDiv.textContent = "";
                                    return;
                                }

                                if (unavailableDates.includes(dateStr)) {
                                    statusDiv.textContent = "Sorry, we are NOT available on this date.";
                                    statusDiv.className = "mt-2 text-red-600 font-semibold";
                                } else {
                                    statusDiv.textContent = "Great! We are available on this date.";
                                    statusDiv.className = "mt-2 text-green-600 font-semibold";
                                }
                            }
                        });
                    });
                </script>


                <!-- Request Service Button -->
                <div class="mt-6">
                    @auth
                        <button type="button" id="request-service-btn"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                            Request This Service
                        </button>
                    @else
                        <a href="{{ route('login') }}">
                            <button type="button"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                                Request This Service
                            </button>
                        </a>
                    @endauth
                </div>

                <!-- Modal (for logged-in users only) -->
                @auth
                    <div id="requestServiceModal"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg w-full max-w-md">
                            <h2 class="text-xl font-semibold mb-4">Send Service Request</h2>

                            <p class="mb-2">Chosen Date:
                                <span id="selected-date-display" class="font-semibold"></span>
                            </p>

                            <textarea id="service-message" class="w-full p-2 border rounded-lg mb-4" placeholder="Optional message"></textarea>

                            <button id="submit-service-request"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Submit
                            </button>
                            <button id="close-modal" class="ml-2 px-4 py-2 rounded border">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            let unavailableDates = @json($service->unavailable_dates ? json_decode($service->unavailable_dates) : []);
                            let selectedDate = null;

                            const modal = document.getElementById("requestServiceModal");
                            const statusDiv = document.getElementById("availability-status");

                            flatpickr("#calendar", {
                                dateFormat: "Y-m-d",
                                minDate: "today",
                                disable: unavailableDates,
                                onDayCreate: function(dObj, dStr, fp, dayElem) {
                                    let date = dayElem.dateObj.toISOString().split("T")[0];
                                    if (unavailableDates.includes(date)) {
                                        dayElem.classList.add("bg-red-200", "text-red-600");
                                    }
                                },
                                onChange: function(selectedDates, dateStr) {
                                    selectedDate = dateStr;

                                    const statusDiv = document.getElementById("availability-status");
                                    if (!dateStr) {
                                        statusDiv.textContent = "";
                                        return;
                                    }

                                    if (unavailableDates.includes(dateStr)) {
                                        statusDiv.textContent = "Sorry, we are NOT available on this date.";
                                        statusDiv.className = "mt-4 text-red-500";
                                    } else {
                                        statusDiv.textContent = "Great! We are available on this date.";
                                        statusDiv.className = "mt-4 text-green-600";
                                    }
                                }
                            });


                            document.getElementById("request-service-btn").addEventListener("click", () => {
                                if (!selectedDate) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Choose a date first",
                                        text: "Please select a date before requesting."
                                    });
                                    return;
                                }
                                document.getElementById("selected-date-display").textContent = selectedDate;
                                modal.classList.remove("hidden");
                            });

                            document.getElementById("close-modal").addEventListener("click", () => {
                                modal.classList.add("hidden");
                            });

                            document.getElementById("submit-service-request").addEventListener("click", () => {
                                if (!selectedDate) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Choose a date first",
                                        text: "Please select a date before requesting."
                                    });
                                    return;
                                }

                                let message = document.getElementById("service-message").value;
                                let pkgTab = document.querySelector(".tab-button.border-b-2, .tab-button.active");
                                let selectedPackage = 'basic';
                                let offeredPrice = {{ $service->basic_price ?? 0 }};

                                if (pkgTab) {
                                    selectedPackage = pkgTab.dataset.tab;
                                    if (selectedPackage === 'basic') offeredPrice = {{ $service->basic_price ?? 0 }};
                                    if (selectedPackage === 'standard') offeredPrice = {{ $service->standard_price ?? 0 }};
                                    if (selectedPackage === 'premium') offeredPrice = {{ $service->premium_price ?? 0 }};
                                }

                                let payload = {
                                    student_service_id: {{ $service->id }},
                                    selected_dates: selectedDate,
                                    selected_package: selectedPackage,
                                    message: message,
                                    offered_price: offeredPrice
                                };

                                fetch("{{ route('service-requests.store') }}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        },
                                        body: JSON.stringify(payload)
                                    })
                                    .then(async res => {
                                        let text = await res.text();
                                        try {
                                            return JSON.parse(text);
                                        } catch (e) {
                                            throw new Error("Server returned invalid JSON");
                                        }
                                    })
                                    .then(data => {
                                        if (!data.success) {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Oops!",
                                                text: data.error || "Something went wrong!"
                                            });
                                            return;
                                        }
                                        Swal.fire({
                                            icon: "success",
                                            title: "Request Sent!",
                                            text: data.message
                                        });
                                        modal.classList.add("hidden");
                                    })
                                    .catch(err => {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "Something went wrong while sending the request."
                                        });
                                    });
                            });
                        });
                    </script>
                @endauth


                <br>
                <hr class="border-t-2 border-gray-300 my-4">

                <!-- Share and Favorite Icons -->
                <div class="mt-4 flex justify-center gap-6">
                    <!-- Share Icon -->
                    <button type="button"
                        class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full transition"
                        title="Share Service" onclick="handleShare(this)"
                        data-url="{{ route('student-services.show', $service) }}">
                        <img src="{{ asset('images/share.png') }}" alt="Share" class="w-5 h-5">
                    </button>
                </div>

                <script>
                    function handleFavourite(serviceId, isLoggedIn) {
                        const heart = document.getElementById('heart-' + serviceId);

                        if (!isLoggedIn) {
                            // Redirect guest to login
                            window.location.href = "{{ route('login') }}";
                            return;
                        }

                        // Toggle heart color visually
                        heart.classList.toggle('text-red-500');
                        heart.classList.toggle('text-gray-400');

                        // Optional: Send AJAX request to save favourite in database
                        fetch('/favourites/toggle/' + serviceId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    service_id: serviceId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Favourite updated', data);
                            })
                            .catch(err => console.error(err));
                    }
                </script>

                <!-- SHARE MODAL -->
                <div id="shareModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 z-50">
                    <div
                        class="bg-white rounded-xl shadow-xl w-80 md:w-96 p-6 transform scale-95 transition-transform duration-300">
                        <!-- Close Button -->
                        <button onclick="closeShareModal()"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Header -->
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Share
                            This
                            Service</h3>

                        <!-- Input + Copy -->
                        <div class="flex items-center border rounded-lg overflow-hidden mb-4">
                            <input type="text" id="shareLinkInput"
                                class="flex-1 px-3 py-2 text-sm text-gray-700 focus:outline-none" readonly>
                            <button onclick="copyShareLink()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium transition-colors duration-200">
                                Copy
                            </button>
                        </div>

                        <!-- Copy Feedback -->
                        <p id="copyMessage" class="text-sm text-green-600 hidden text-center mb-2">Link copied!
                        </p>
                    </div>
                </div>


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

                <!-- Favorite Icon -->
                <button class="text-gray-600 hover:text-gray-800 transition duration-300 ease-in-out">
                    <i class="fas fa-heart text-2xl"></i>
                    <p class="text-xs mt-1">Favorite</p>
                </button>
            </div>
            </div>
        </aside>

        <script>
            const tabs = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const mainPrice = document.querySelector('.sticky-sidebar .text-4xl.font-bold');

            const tabColors = {
                basic: '#367588',
                standard: '#F0B13B',
                premium: '#E7180B'
            };

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const selectedTab = tab.getAttribute('data-tab');

                    // Hide all tab contents
                    tabContents.forEach(content => content.classList.add('hidden'));

                    // Show the selected tab content
                    document.getElementById(selectedTab).classList.remove('hidden');

                    // Reset all tabs styles
                    tabs.forEach(t => {
                        t.classList.remove('border-b-2', 'active');
                        t.classList.add('text-gray-600');
                        t.style.backgroundColor = '';
                        t.style.color = '';
                    });

                    // Apply styles for selected tab
                    tab.classList.add('border-b-2', 'active');
                    tab.style.backgroundColor = tabColors[selectedTab];
                    tab.style.color = 'white';

                    // Update main price dynamically
                    const priceDiv = document.getElementById(selectedTab).querySelector(
                    'div.text-xl.font-bold');
                    if (priceDiv) {
                        mainPrice.textContent = priceDiv.textContent;
                        mainPrice.style.color = tabColors[selectedTab]; // Change price color too
                    }
                });
            });

            // Default tab
            @if ($service->basic_price)
                document.querySelector('[data-tab="basic"]').click();
            @elseif ($service->standard_price)
                document.querySelector('[data-tab="standard"]').click();
            @elseif ($service->premium_price)
                document.querySelector('[data-tab="premium"]').click();
            @endif
        </script>





        </div> <!-- grid -->
    </main>

    @include('layouts.footer')

</body>

</html>
