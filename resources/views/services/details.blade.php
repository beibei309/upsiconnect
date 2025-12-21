<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $service->title ?? 'Service Page' }} - S2U</title>

    {{-- Fonts & CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        h1,
        h2,
        h3,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (min-width: 1024px) {
            .sticky-sidebar {
                position: sticky;
                top: 100px;
            }
        }

        /* Custom Flatpickr Styling */
        .flatpickr-calendar {
            border-radius: 1rem;
            border: none;
            box-shadow: none;
            margin: 0 auto;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }

        .rich-text ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .rich-text ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body class="antialiased text-slate-800">

    @php
        $hasActiveRequest = false;
        if (auth()->check()) {
            $hasActiveRequest = \App\Models\ServiceRequest::where('requester_id', auth()->id())
                ->where('provider_id', $service->user_id)
                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                ->exists();
        }
    @endphp

    @include('layouts.navbar')

    <div class="bg-white border-b border-gray-200 pt-24 pb-6">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                    <li class="inline-flex items-center"><a href="{{ route('dashboard') }}"
                            class="hover:text-indigo-600"><i class="fa-solid fa-house mr-2"></i> Home</a></li>
                    <li><i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i><a
                            href="{{ route('services.index') }}" class="hover:text-indigo-600">Find Services</a></li>
                    <li><i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i><span
                            class="font-medium text-gray-800">{{ $service->title }}</span></li>
                </ol>
            </nav>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            {{-- LEFT COLUMN (Service Details) --}}
            <div class="lg:col-span-8 space-y-8">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight mb-4">{{ $service->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <span class="font-semibold text-slate-900">{{ $service->user->name }}</span> |
                        <span class="text-slate-500"><i class="fa-solid fa-star text-yellow-400"></i>
                            {{ $service->rating ?? '0.0' }}</span>

                        {{-- ADDED STATUS BADGE --}}
                        @if ($service->status === 'available')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                Available
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                Unavailable
                            </span>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-100 bg-white">
                    <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                        class="w-full h-[400px] object-cover">
                </div>

                <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 border-b border-gray-100 pb-2">Description</h2>
                    <div class="prose prose-slate max-w-none text-gray-600 rich-text">{!! $service->description !!}</div>
                </section>

              {{-- Helper Profile Section --}}
<section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
    <div class="flex flex-col md:flex-row gap-8 items-start">

        {{-- Left: Profile Image & Badge --}}
        <div class="relative mx-auto md:mx-0 flex-shrink-0 group">
            
            {{-- 1. WRAPPER FOR BLUR LOGIC --}}
            <div class="relative">
                @if ($service->user->profile_photo_path)
                    <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                        class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 border-white shadow-lg transition-all duration-300 
                        {{-- BLUR IF GUEST --}}
                        @guest blur-md brightness-90 @endguest">
                @else
                    <div
                        class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-indigo-600 flex items-center justify-center text-3xl md:text-4xl text-white font-bold border-4 border-white shadow-lg 
                        {{-- BLUR IF GUEST --}}
                        @guest blur-md brightness-90 @endguest">
                        {{ strtoupper(substr($service->user->name, 0, 1)) }}
                    </div>
                @endif

                {{-- LOCK ICON OVERLAY FOR GUESTS --}}
                @guest
                    <div class="absolute inset-0 flex items-center justify-center z-10">
                        <div class="bg-black/30 p-2 rounded-full">
                            <i class="fas fa-lock text-white text-lg"></i>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Verified Badge (Only show if logged in, or keep visible but on top of blur) --}}
            @if ($service->user->trust_badge ?? false)
                <div class="absolute bottom-1 right-1 bg-blue-500 text-white w-7 h-7 flex items-center justify-center rounded-full border-2 border-white shadow-sm z-20"
                    title="Verified Student">
                    <i class="fas fa-check text-xs"></i>
                </div>
            @endif
        </div>

        {{-- Right: Info & Stats --}}
        <div class="flex-1 w-full text-center md:text-left">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{-- Optional: Mask name for guests if you want extra privacy --}}
                    @guest
                        {{ Str::mask($service->user->name, '*', 3) }}
                    @else
                        {{ $service->user->name }}
                    @endguest
                </h3>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 text-sm">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-indigo-50 text-indigo-700">
                        <i class="fa-solid fa-graduation-cap mr-1.5 text-xs"></i>
                        {{ $service->user->faculty ?? 'Faculty of Computing' }}
                    </span>
                    <span class="text-gray-400 hidden sm:inline">•</span>
                    <span class="text-gray-500">Member since
                        {{ $service->user->created_at->format('M Y') }}</span>
                </div>
            </div>

            {{-- Bio Box --}}
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-left relative">
                <i class="fa-solid fa-quote-left text-slate-200 text-2xl absolute top-3 left-3 -z-0"></i>
                <p class="text-gray-600 italic text-sm relative z-10 pl-6">
                    "{{ $service->user->bio ?? 'Hi! I am a dedicated student at UPSI looking to help the community. I ensure all tasks are completed with care and punctuality.' }}"
                </p>
            </div>

            {{-- Quick Stats Row --}}
            <div class="grid grid-cols-2 gap-4 mt-5 pt-5 border-t border-gray-100">
                {{-- Stats content here --}}
            </div>

            <div class="mt-5 text-center md:text-left">
                {{-- 2. LOGIC FOR VIEW PROFILE LINK --}}
                @auth
                    {{-- User IS logged in --}}
                    <a href="{{ route('students.profile', $service->user) }}"
                        class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                        View Full Profile <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                    </a>
                @else
                    {{-- User is GUEST (Redirect to login) --}}
                    <a href="{{ route('login') }}" 
                       onclick="return confirm('Please sign in to view the full profile details.')"
                        class="text-sm font-bold text-gray-500 hover:text-indigo-600 hover:underline transition-colors cursor-pointer">
                        <i class="fas fa-lock mr-1 text-xs"></i> Sign in to view profile
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

                {{-- Reviews Section --}}
               <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-900">
            Reviews ({{ $reviews->count() }})
        </h2>

        {{-- Show Service Rating Summary --}}
        @if ($reviews->count() > 0)
            <div class="flex items-center gap-2 bg-yellow-50 px-3 py-1 rounded-lg border border-yellow-100">
                <i class="fas fa-star text-yellow-500"></i>
                <span class="font-bold text-slate-800">{{ number_format($service->rating, 1) }}</span>
                <span class="text-xs text-gray-500">/ 5.0</span>
            </div>
        @endif
    </div>

    @if (isset($reviews) && count($reviews) > 0)
        <div class="space-y-6">
            @foreach ($reviews as $review)
                <div class="border-b border-gray-50 pb-6 last:border-0 last:pb-0">

                    {{-- 1. Client Review --}}
                    <div class="flex items-start gap-3">
                        {{-- Avatar Client --}}
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm uppercase">
                                {{ substr($review->reviewer->name ?? 'U', 0, 1) }}
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <span class="font-bold text-slate-900 text-sm">
                                    {{-- LOGIC: CENSOR NAME IF NOT SIGNED IN --}}
                                    @auth
                                        {{-- User is signed in: Show full name --}}
                                        {{ $review->reviewer->name ?? 'User' }}
                                    @else
                                        {{-- User is NOT signed in: Show First Letter + Stars (e.g. A****) --}}
                                        {{ substr($review->reviewer->name ?? 'User', 0, 1) . '****' }}
                                    @endauth
                                </span>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex text-yellow-400 text-xs my-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>

                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    </div>

                    {{-- 2. Helper Reply (Display only if reply exists) --}}
                    @if ($review->reply)
                        <div class="mt-4 ml-2 pl-8 border-l-2 border-indigo-100 relative">
                            <div class="bg-slate-50 p-4 rounded-r-xl rounded-bl-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1">
                                        Reply from seller: {{ $service->user->name }}
                                        @if ($service->user->trust_badge)
                                            <i class="fas fa-check-circle text-[10px]"></i>
                                        @endif
                                    </span>

                                    @if ($review->replied_at)
                                        <span class="text-[10px] text-gray-400">•
                                            {{ \Carbon\Carbon::parse($review->replied_at)->diffForHumans() }}</span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 italic">"{{ $review->reply }}"</p>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-regular fa-comments text-gray-300 text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm">No reviews yet for this service.</p>
        </div>
    @endif
</section>
            </div>

            {{-- RIGHT COLUMN (Booking System) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6" x-data="bookingSystem()" x-init="init()">

                    {{-- 1. CALENDAR MODAL (Hidden by default) --}}
                    <template x-teleport="body">
                        <div x-show="showFullCalendar"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
                            style="display: none;" x-transition.opacity>
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all"
                                @click.away="showFullCalendar = false" x-transition.scale>
                                <div class="flex justify-between items-center p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-slate-800">Select Date</h3>
                                    <button @click="showFullCalendar = false"
                                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="p-4 flex justify-center">
                                    <div id="full-calendar-container"></div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- 2. MAIN BOOKING CARD --}}
                    <div
                        class="bg-white rounded-2xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden relative">

                        <div class="grid grid-cols-3 border-b border-gray-200 bg-gray-50">
                            @if ($service->basic_price)
                                <button @click="switchPackage('basic')"
                                    :class="currentPackage === 'basic' ?
                                        'border-b-2 border-teal-600 text-teal-600 font-bold bg-white' :
                                        'text-gray-500 hover:text-gray-700'"
                                    class="py-4 text-sm transition-all border-b-2 border-transparent">
                                    Basic
                                </button>
                            @endif
                            @if ($service->standard_price)
                                <button @click="switchPackage('standard')"
                                    :class="currentPackage === 'standard' ?
                                        'border-b-2 border-yellow-500 text-yellow-600 font-bold bg-white' :
                                        'text-gray-500 hover:text-gray-700'"
                                    class="py-4 text-sm transition-all border-b-2 border-transparent">
                                    Standard
                                </button>
                            @endif
                            @if ($service->premium_price)
                                <button @click="switchPackage('premium')"
                                    :class="currentPackage === 'premium' ?
                                        'border-b-2 border-red-600 text-red-600 font-bold bg-white' :
                                        'text-gray-500 hover:text-gray-700'"
                                    class="py-4 text-sm transition-all border-b-2 border-transparent">
                                    Premium
                                </button>
                            @endif
                        </div>

                        <div class="p-6">
                            {{-- Price & Simple Info Display --}}
                            <div class="flex flex-col items-end mb-6 text-right">
                                <span class="font-bold text-gray-400 text-xs uppercase tracking-wider mb-1">
                                    <span x-text="isSessionBased ? 'Total Estimate' : 'Task Price'"></span>
                                </span>

                                {{-- Price --}}
                                <span class="text-4xl font-extrabold" :class="priceColorClass"
                                    x-text="'RM' + calculateTotal()"></span>

                                {{-- 🟢 UPDATED: Simple Data Display (No labels) --}}
                                <div class="text-sm font-medium text-gray-500 mt-1 flex items-center gap-1"
                                    x-show="packages[currentPackage].duration || packages[currentPackage].frequency">
                                    <span x-text="packages[currentPackage].duration"></span>

                                    {{-- Show divider/text only if both exist --}}
                                    <span
                                        x-show="packages[currentPackage].duration && packages[currentPackage].frequency">
                                        per
                                    </span>

                                    <span x-text="packages[currentPackage].frequency"></span>
                                </div>
                            </div>

                            {{-- Description Box --}}
                            <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-100 text-sm" x-transition>
                                <div class="text-slate-700 prose prose-sm max-w-none rich-text"
                                    x-html="packages[currentPackage].description || 'No description provided.'"></div>
                            </div>

                            {{-- Duration (Session Based Only) --}}
                            <div class="mb-6" x-show="isSessionBased">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase">Duration</label>
                                    <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded"
                                        x-text="selectedDuration + ' Hours'"></span>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="h in [1, 2, 3, 4]" :key="h">
                                        <button @click="selectDuration(h)" type="button"
                                            class="py-2.5 rounded-xl border text-sm font-bold transition-all"
                                            :class="selectedDuration === h ?
                                                'bg-slate-800 text-white border-slate-800 shadow-md transform -translate-y-0.5' :
                                                'bg-white text-gray-600 border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                            <span x-text="h + 'h'"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="w-full h-px bg-gray-100 mb-6"></div>

                            {{-- Date Scroller --}}
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="text-xs font-bold text-gray-700 uppercase">Select Date</label>
                                    <button @click="openCalendar()"
                                        class="text-xs text-indigo-600 font-bold hover:text-indigo-800 flex items-center gap-1">
                                        <i class="fa-regular fa-calendar"></i> Full Calendar
                                    </button>
                                </div>

                                <div class="relative group">
                                    {{-- Prev Button --}}
                                    <button type="button"
                                        @click="$refs.dateScroller.scrollBy({ left: -200, behavior: 'smooth' })"
                                        class="absolute -left-2 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md border border-gray-100 text-gray-600 hover:text-indigo-600 hover:scale-110 transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </button>

                                    <div x-ref="dateScroller"
                                        class="flex space-x-2 overflow-x-auto pb-4 pt-1 px-1 no-scrollbar scroll-smooth">
                                        <template x-for="day in upcomingDays" :key="day.dateStr">
                                            <button @click="selectDate(day)" :disabled="!day.isAvailable"
                                                class="flex flex-col items-center justify-center min-w-[4.5rem] py-3 rounded-2xl border transition-all flex-shrink-0 relative group/date"
                                                :class="{
                                                    'bg-slate-900 text-white border-slate-900 shadow-lg shadow-slate-900/20 transform -translate-y-1': selectedDate ===
                                                        day.dateStr,
                                                    'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:shadow-md': selectedDate !==
                                                        day.dateStr && day.isAvailable,
                                                    'bg-gray-50 text-gray-300 border-gray-100 cursor-not-allowed opacity-60':
                                                        !day.isAvailable
                                                }">
                                                <span
                                                    class="text-[10px] font-bold uppercase tracking-wider mb-1 opacity-80"
                                                    x-text="day.dayName"></span>
                                                <span class="text-lg font-black" x-text="day.dayNumber"></span>

                                                {{-- Today Indicator --}}
                                                <span
                                                    x-show="new Date().toDateString() === new Date(day.dateStr).toDateString()"
                                                    class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full"
                                                    :class="selectedDate === day.dateStr ? 'bg-indigo-400' : 'bg-indigo-500'"></span>
                                            </button>
                                        </template>
                                    </div>

                                    {{-- Next Button --}}
                                    <button type="button"
                                        @click="$refs.dateScroller.scrollBy({ left: 200, behavior: 'smooth' })"
                                        class="absolute -right-2 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md border border-gray-100 text-gray-600 hover:text-indigo-600 hover:scale-110 transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Time Slots --}}
                            <div x-show="selectedDate && isSessionBased" x-transition class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Start Time</label>
                                <div class="flex flex-wrap gap-2" x-show="timeSlots.length > 0">
                                    <template x-for="slot in timeSlots" :key="slot.time">
                                        <button type="button" @click="selectedTime = slot.time"
                                            :disabled="!slot.available"
                                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all border"
                                            :class="{
                                                'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20': selectedTime ===
                                                    slot.time,
                                                'bg-white text-slate-600 border-slate-200 hover:border-indigo-400 hover:text-indigo-600': selectedTime !==
                                                    slot.time && slot.available,
                                                'bg-slate-50 text-slate-300 border-slate-100 cursor-not-allowed': !slot
                                                    .available
                                            }">
                                            <span x-text="formatTimeOnly(slot.time)"></span>
                                        </button>
                                    </template>
                                </div>
                                <div x-show="timeSlots.length === 0"
                                    class="text-sm bg-orange-50 text-orange-600 px-3 py-2 rounded-lg border border-orange-100">
                                    <i class="fa-regular fa-circle-xmark mr-1"></i> No times available.
                                </div>
                            </div>

                            {{-- Task Based Feedback --}}
                            <div x-show="selectedDate && !isSessionBased" x-transition
                                class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-indigo-900"
                                        x-text="new Date(selectedDate).toDateString()"></p>
                                    <p class="text-xs text-indigo-700">Full day service allocated.</p>
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            @auth
                                @if ($service->status === 'available')
                                    <button @click="submitBooking()"
                                        :disabled="!selectedDate || (isSessionBased && !selectedTime)"
                                        class="group w-full py-4 rounded-xl font-bold text-white shadow-xl transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:shadow-none disabled:bg-gray-300 disabled:cursor-not-allowed hover:-translate-y-1"
                                        :class="(!selectedDate || (isSessionBased && !selectedTime)) ? '' :
                                        'bg-slate-900 hover:bg-indigo-600 hover:shadow-indigo-500/30'">
                                        <span>Request Appointment</span>
                                        <i class="fa-solid fa-arrow-right text-sm transition-transform group-hover:translate-x-1"
                                            x-show="!(!selectedDate || (isSessionBased && !selectedTime))"></i>
                                    </button>
                                @else
                                    <button disabled
                                        class="w-full py-4 rounded-xl font-bold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-ban text-sm"></i> <span>Service Unavailable</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full py-4 rounded-xl font-bold text-white shadow-lg transition-all flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 hover:-translate-y-0.5">
                                    <span>Sign in to Request</span> <i class="fa-solid fa-right-to-bracket text-sm"></i>
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- 3. CONTACT & INFO CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1 h-5 bg-indigo-500 rounded-full"></span> Contact
                        </h3>

                        {{-- WhatsApp Button --}}
                        @php
                            $rawPhone = $service->user->phone_number ?? ($service->user->phone ?? '');
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            if (substr($cleanPhone, 0, 1) === '0') {
                                $cleanPhone = '60' . substr($cleanPhone, 1);
                            }
                            $whatsappUrl =
                                "https://wa.me/{$cleanPhone}?text=Hi, I am interested in your service: " .
                                urlencode($service->title);
                        @endphp

                        @if (!empty($cleanPhone))
                            <a href="{{ $whatsappUrl }}" target="_blank"
                                class="flex items-center justify-center w-full py-3 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-xl font-bold transition-all shadow-md shadow-green-500/20 hover:shadow-green-500/40 hover:-translate-y-0.5 mb-6 group">
                                <i
                                    class="fa-brands fa-whatsapp text-xl mr-2 transition-transform group-hover:scale-110"></i>
                                Chat on WhatsApp
                            </a>
                        @endif

                        {{-- Collapsible Operating Hours --}}
                        <div x-data="{ showHours: false }" class="border-t border-gray-100 pt-5 mb-6">
                            <button @click="showHours = !showHours"
                                class="flex items-center justify-between w-full text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">
                                <span class="flex items-center gap-2">
                                    <i class="fa-regular fa-clock text-gray-400"></i> Operating Hours
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-300"
                                    :class="showHours ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="showHours" x-collapse style="display: none;">
                                <ul class="space-y-2 text-sm mt-3 pl-6 border-l-2 border-gray-50">
                                    @php
                                        $daysMap = [
                                            'mon' => 'Mon',
                                            'tue' => 'Tue',
                                            'wed' => 'Wed',
                                            'thu' => 'Thu',
                                            'fri' => 'Fri',
                                            'sat' => 'Sat',
                                            'sun' => 'Sun',
                                        ];
                                        $schedule = $service->operating_hours ?? [];
                                    @endphp
                                    @foreach ($daysMap as $key => $dayName)
                                        @php
                                            $d = $schedule[$key] ?? [];
                                            $isOpen = isset($d['enabled']) && $d['enabled'] == true;
                                            $isToday = strtolower(now()->format('D')) == strtolower($dayName);
                                        @endphp
                                        <li
                                            class="flex justify-between items-center {{ $isToday ? 'text-indigo-600 font-bold' : 'text-gray-500' }}">
                                            <span class="w-10">{{ $dayName }}</span>
                                            @if ($isOpen)
                                                <span>{{ $d['start'] ?? '09:00' }} - {{ $d['end'] ?? '17:00' }}</span>
                                            @else
                                                <span
                                                    class="text-xs bg-gray-100 px-1.5 py-0.5 rounded text-gray-400">Closed</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Utility Buttons Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Share --}}
                            <button onclick="handleShare(this)"
                                data-url="{{ route('student-services.show', $service->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Share
                            </button>

                            {{-- Save / Favourite --}}
                            @php $isFav = auth()->check() && $service->is_favourited; @endphp
                            <button
                                onclick="handleFavourite({{ $service->id }}, {{ auth()->check() ? 'true' : 'false' }})"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-xl border border-gray-200 text-sm font-bold transition-all group
                    {{ $isFav ? 'bg-red-50 text-red-500 border-red-100' : 'text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                                <i id="heart-{{ $service->id }}"
                                    class="{{ $isFav ? 'fas' : 'far' }} fa-heart transition-transform group-active:scale-90"></i>
                                <span id="text-{{ $service->id }}">{{ $isFav ? 'Saved' : 'Save' }}</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')

    {{-- Share Modal --}}
    <div id="shareModal"
        class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-80 p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">Share Service</h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="flex items-center border rounded-lg overflow-hidden bg-gray-50">
                <input type="text" id="shareLinkInput"
                    class="flex-1 px-3 py-2 text-sm bg-transparent outline-none text-gray-600" readonly>
                <button onclick="copyShareLink()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium">Copy</button>
            </div>
            <p id="copyMessage" class="text-xs text-green-600 mt-2 text-center opacity-0 transition-opacity">Copied!
            </p>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('booking', {
                showCalendarModal: false
            });
        });

        function bookingSystem() {
            return {
                hasActiveRequest: @json($hasActiveRequest),
                isSessionBased: {{ $service->session_duration ? 'true' : 'false' }},

                // --- DATA ---
                holidays: @json(
                    $service->unavailable_dates
                        ? (is_array($service->unavailable_dates)
                            ? $service->unavailable_dates
                            : json_decode($service->unavailable_dates))
                        : []
                ),
                schedule: @json($service->operating_hours ?? []),
                bookedSlots: @json($bookedAppointments ?? []),
                manualBlocks: @json($manualBlocks ?? []),

                // 🟢 NEW: Full Package Objects
                packages: {
                    basic: {
                        price: {{ $service->basic_price ?? 0 }},
                        description: `{!! $service->basic_description ?? '' !!}`,
                        duration: "{{ $service->basic_duration ?? 'N/A' }}",
                        frequency: "{{ $service->basic_frequency ?? 'N/A' }}"
                    },
                    standard: {
                        price: {{ $service->standard_price ?? 0 }},
                        description: `{!! $service->standard_description ?? '' !!}`,
                        duration: "{{ $service->standard_duration ?? 'N/A' }}",
                        frequency: "{{ $service->standard_frequency ?? 'N/A' }}"
                    },
                    premium: {
                        price: {{ $service->premium_price ?? 0 }},
                        description: `{!! $service->premium_description ?? '' !!}`,
                        duration: "{{ $service->premium_duration ?? 'N/A' }}",
                        frequency: "{{ $service->premium_frequency ?? 'N/A' }}"
                    }
                },

                currentPackage: '{{ $service->basic_price ? 'basic' : ($service->standard_price ? 'standard' : 'premium') }}',
                selectedDuration: 1,
                selectedDate: null,
                selectedTime: null,
                upcomingDays: [],
                timeSlots: [],
                sessionDuration: {{ $service->session_duration ?? 60 }},
                showFullCalendar: false,
                calendarInstance: null,

                // --- COMPUTED PROPERTIES ---
                get priceColorClass() {
                    if (this.currentPackage === 'basic') return 'text-teal-600';
                    if (this.currentPackage === 'standard') return 'text-yellow-500';
                    if (this.currentPackage === 'premium') return 'text-red-600';
                    return 'text-indigo-600';
                },

                // --- METHODS ---
                init() {
                    this.generateCalendar();
                },

                calculateTotal() {
                    // Use the new packages object structure
                    return (this.packages[this.currentPackage].price * this.selectedDuration).toFixed(2);
                },

                selectDuration(hours) {
                    this.selectedDuration = hours;
                    this.selectedTime = null;
                    if (this.selectedDate) {
                        const dayObj = this.upcomingDays.find(d => d.dateStr === this.selectedDate);
                        if (dayObj) {
                            this.generateTimeSlots(dayObj.dayKey);
                        } else {
                            const dateObj = new Date(this.selectedDate);
                            const jsDays = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                            this.generateTimeSlots(jsDays[dateObj.getDay()]);
                        }
                    }
                },

                generateCalendar() {
                    const days = [];
                    const today = new Date();
                    const jsDays = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                    for (let i = 0; i < 14; i++) {
                        const d = new Date(today);
                        d.setDate(today.getDate() + i);

                        const year = d.getFullYear();
                        const month = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        const dateStr = `${year}-${month}-${day}`;

                        const dayOfWeekIndex = d.getDay();
                        const dayKey = jsDays[dayOfWeekIndex];

                        let isAvailable = true;
                        if (this.holidays.includes(dateStr)) isAvailable = false;

                        const config = this.schedule[dayKey];
                        if (!config || !config.enabled || config.enabled == 'false') isAvailable = false;

                        days.push({
                            dateStr: dateStr,
                            dayName: dayNames[dayOfWeekIndex],
                            dayNumber: day,
                            dayKey: dayKey,
                            isAvailable: isAvailable
                        });
                    }
                    this.upcomingDays = days;
                },

                selectDate(dayObj) {
                    if (!dayObj.isAvailable) return;
                    this.selectedDate = dayObj.dateStr;
                    this.selectedTime = null;
                    this.generateTimeSlots(dayObj.dayKey);
                },
                formatTimeOnly(timeStr) {
                    if (!timeStr) return '';
                    let [h, m] = timeStr.split(':').map(Number);
                    let ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12;
                    h = h ? h : 12;
                    return `${h}:${m.toString().padStart(2, '0')} ${ampm}`;
                },

                generateTimeSlots(dayKey) {
                    this.timeSlots = [];
                    const config = this.schedule[dayKey];

                    // If day is disabled in settings, stop.
                    if (!config || !config.enabled) return;

                    // Parse Operating Hours (e.g., 09:00 to 17:00)
                    let [startH, startM] = config.start.split(':').map(Number);
                    let [endH, endM] = config.end.split(':').map(Number);

                    let currentMinutes = startH * 60 + startM;
                    let endMinutes = endH * 60 + endM;

                    // Step is determined by the Service's Session Duration (e.g., 60 mins)
                    // But the USER's selected duration (e.g., 2 hours) determines if they fit in the gap
                    let stepMinutes = this.sessionDuration;
                    let durationMinutes = this.selectedDuration * 60; // How long the student wants to book

                    // Filter real bookings for the selected date to optimize the loop
                    let daysBookings = this.bookedSlots.filter(slot => slot.date === this.selectedDate);

                    while (currentMinutes + durationMinutes <= endMinutes) {

                        let h = Math.floor(currentMinutes / 60);
                        let m = currentMinutes % 60;
                        let timeStr = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`; // "14:00"

                        let proposedStart = currentMinutes;
                        let proposedEnd = currentMinutes + durationMinutes;

                        // CHECK 1: Real Database Bookings (Overlap Check)
                        let isBooked = daysBookings.some(booking => {
                            let [bStartH, bStartM] = booking.start_time.split(':').map(Number);
                            let [bEndH, bEndM] = booking.end_time.split(':').map(Number);

                            let bookingStart = bStartH * 60 + bStartM;
                            let bookingEnd = bEndH * 60 + bEndM;

                            // If the requested time overlaps with any part of a booked slot
                            return (proposedStart < bookingEnd) && (proposedEnd > bookingStart);
                        });

                        // CHECK 2: Manual Blocks (Exact Start Time Match)
                        // The Helper blocked "2025-12-20 14:00". If this slot is 14:00, block it.
                        let blockKey = `${this.selectedDate} ${timeStr}`;
                        let isManuallyBlocked = this.manualBlocks.includes(blockKey);

                        // CHECK 3: Manual Blocks (Overlap Logic - Optional but recommended)
                        // If the helper blocked 14:00 (1 hour block), and student wants 2 hours starting at 13:00,
                        // the student's 13:00-15:00 overlaps with the blocked 14:00-15:00.
                        // For simplicity, we stick to "Is the start time blocked?" usually, 
                        // but checking if any blocked start time falls within our proposed duration is safer:
                        if (!isManuallyBlocked) {
                            // Check if any manual block falls inside our proposed time range
                            isManuallyBlocked = this.manualBlocks.some(blockedKey => {
                                if (!blockedKey.startsWith(this.selectedDate)) return false;
                                let blockedTime = blockedKey.split(' ')[1]; // "14:00"
                                let [blkH, blkM] = blockedTime.split(':').map(Number);
                                let blkMin = blkH * 60 + blkM;
                                // Assuming manual blocks are 1 'session_duration' unit long
                                let blkEnd = blkMin + this.sessionDuration;

                                return (proposedStart < blkEnd) && (proposedEnd > blkMin);
                            });
                        }

                        this.timeSlots.push({
                            time: timeStr,
                            available: !isBooked && !isManuallyBlocked
                        });

                        currentMinutes += stepMinutes;
                    }
                },

                switchPackage(pkg) {
                    this.currentPackage = pkg;
                    this.selectedTime = null;
                },

                formatTimeDisplay(timeStr) {
                    if (!timeStr) return '';
                    let [h, m] = timeStr.split(':').map(Number);
                    let startMinutes = h * 60 + m;

                    // 🟢 NEW LOGIC: If Task Based, only show Start Time
                    if (!this.isSessionBased) {
                        return this.minutesToTime(startMinutes);
                    }

                    // If Session Based, show Range
                    let endMinutes = startMinutes + (this.selectedDuration * 60);
                    return `${this.minutesToTime(startMinutes)} - ${this.minutesToTime(endMinutes)}`;
                },

                minutesToTime(totalMinutes) {
                    let h = Math.floor(totalMinutes / 60);
                    let m = totalMinutes % 60;
                    let ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12;
                    h = h ? h : 12;
                    return `${h}:${m.toString().padStart(2, '0')} ${ampm}`;
                },
                calculateEndTime(startTime) {
                    if (!startTime) return '00:00';

                    // Split the time (e.g., "14:30")
                    let [h, m] = startTime.split(':').map(Number);

                    // Add the session duration (e.g., 60 mins)
                    let totalMinutes = (h * 60) + m + this.sessionDuration;

                    // Convert back to HH:MM
                    let endH = Math.floor(totalMinutes / 60);
                    let endM = totalMinutes % 60;

                    // Handle overflow (if it goes past midnight, theoretically)
                    endH = endH % 24;

                    return `${endH.toString().padStart(2, '0')}:${endM.toString().padStart(2, '0')}`;
                },

                submitBooking() {
                    @auth
                    if (this.hasActiveRequest) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Limit Reached',
                            text: 'You already have an active request.',
                            confirmButtonColor: '#f59e0b'
                        });
                        return;
                    }

                    // --- 1. Prepare Data for Task vs Session ---
                    let displayTime = this.isSessionBased ? this.formatTimeDisplay(this.selectedTime) :
                        'Anytime (Full Day)';
                    let displayDuration = this.isSessionBased ? this.selectedDuration + ' Hours' : 'Task Based';

                    // Define times to send to backend
                    let sendStartTime = this.isSessionBased ? this.selectedTime : '00:00';
                    let sendEndTime = this.isSessionBased ? this.calculateEndTime(this.selectedTime) : '23:59';

                    // --- 2. Build Modal HTML ---
                    let detailsHtml = `
            <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm mb-4">
                <p class="mb-1"><strong>Date:</strong> ${this.selectedDate}</p>`;

                    // Only show Time row if session based
                    if (this.isSessionBased) {
                        detailsHtml += `<p class="mb-1"><strong>Time:</strong> ${displayTime}</p>
                            <p class="mb-1"><strong>Duration:</strong> ${displayDuration}</p>`;
                    } else {
                        detailsHtml += `<p class="mb-1"><strong>Type:</strong> Daily Task Request</p>`;
                    }

                    detailsHtml += `
                <p class="mb-1"><strong>Package:</strong> ${this.currentPackage.toUpperCase()}</p>
                <p class="text-lg font-bold text-indigo-600 mt-2">Total: RM${this.calculateTotal()}</p>
            </div>
            <div class="text-left">
                <label class="block text-sm font-bold text-gray-700 mb-1">Message to Helper (Required)</label>
                <textarea id="swal-message-input" 
                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3" 
                    rows="3" 
                    placeholder="Please describe the task details here..."></textarea>
            </div>`;

                    Swal.fire({
                        title: 'Confirm Request?',
                        html: detailsHtml,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Send Request',
                        confirmButtonColor: '#4f46e5',
                        preConfirm: () => {
                            const msg = document.getElementById('swal-message-input').value;
                            if (!msg) Swal.showValidationMessage(
                                'Please write a message describing your request');
                            return msg;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const userNote = result.value;
                            const systemInfo = this.isSessionBased ? `Duration: ${this.selectedDuration} Hours` :
                                'One-off Task Request';
                            const finalMessage = `${systemInfo}\n\nUser Note: ${userNote}`;

                            fetch("{{ route('service-requests.store') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        student_service_id: {{ $service->id }},
                                        selected_dates: this.selectedDate,
                                        start_time: sendStartTime, // Sends 00:00 if task based
                                        end_time: sendEndTime, // Sends 23:59 if task based
                                        message: finalMessage,
                                        selected_package: this.currentPackage,
                                        offered_price: this.calculateTotal()
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Success', 'Request sent!', 'success').then(() => location
                                            .reload());
                                    } else {
                                        Swal.fire('Error', data.message || 'Error occurred.', 'error');
                                    }
                                });
                        }
                    });
                @endauth
                @guest window.location.href = "{{ route('login') }}";
            @endguest

        }
        }
        }

        function handleShare(btn) {
            const modal = document.getElementById('shareModal');
            document.getElementById('shareLinkInput').value = btn.dataset.url;
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('div').classList.remove('scale-95');
            modal.querySelector('div').classList.add('scale-100');
        }

        function closeShareModal() {
            const modal = document.getElementById('shareModal');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('opacity-0', 'pointer-events-none'), 150);
        }

        function copyShareLink() {
            const input = document.getElementById('shareLinkInput');
            input.select();
            document.execCommand("copy");
            const msg = document.getElementById('copyMessage');
            msg.classList.remove('opacity-0');
            setTimeout(() => msg.classList.add('opacity-0'), 2000);
        }

        function handleFavourite(serviceId, loggedIn) {
            if (!loggedIn) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            const icon = document.getElementById('heart-' + serviceId);
            const text = document.getElementById('text-' + serviceId);

            fetch("{{ route('favorites.services.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        service_id: serviceId
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (!data.success) return;

                    if (data.favorited) {
                        // ❤️ UI update
                        icon.className = "fas fa-heart";
                        icon.parentElement.classList.remove('text-gray-500');
                        icon.parentElement.classList.add('text-red-500');
                        text.innerText = "Saved";

                        // ✅ SweetAlert success
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: 'Service added to your favourites',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });

                    } else {
                        // 💔 UI update
                        icon.className = "far fa-heart";
                        icon.parentElement.classList.remove('text-red-500');
                        icon.parentElement.classList.add('text-gray-500');
                        text.innerText = "Save";

                        // ⚠️ SweetAlert removed
                        Swal.fire({
                            icon: 'info',
                            title: 'Removed',
                            text: 'Service removed from favourites',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message || 'Unable to update favourite'
                    });
                });
        }
    </script>
</body>

</html>
