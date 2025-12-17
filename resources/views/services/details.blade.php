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
                <section
                    class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">About The Helper</h2>
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="flex-shrink-0">
                            @if ($service->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full bg-indigo-600 flex items-center justify-center text-3xl text-white font-bold shadow-sm">
                                    {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2">
                            <h3 class="text-lg font-bold text-slate-900">{{ $service->user->name }}</h3>
                            <p class="text-gray-600 italic">"{{ $service->user->bio ?? 'Ready to help you!' }}"</p>
                        </div>
                    </div>
                </section>

                {{-- Reviews Section --}}
                <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Reviews
                        ({{ $service->user->reviewsReceived()->count() }})</h2>
                    @if (isset($reviews) && count($reviews) > 0)
                        @foreach ($reviews as $review)
                            <div class="mb-4 border-b pb-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold">{{ $review->reviewer->name }}</span>
                                    <span class="text-yellow-500 text-sm"><i class="fas fa-star"></i>
                                        {{ $review->rating }}</span>
                                </div>
                                <p class="text-gray-600 text-sm mt-1">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No reviews yet.</p>
                    @endif
                </section>
            </div>

            {{-- RIGHT COLUMN (Booking System) --}}
            <div class="lg:col-span-4">
                <div class="sticky-sidebar space-y-4" x-data="bookingSystem()" x-init="init()">

                    {{-- CALENDAR MODAL --}}
                    <template x-teleport="body">
                        <div x-show="showFullCalendar"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                            style="display: none;" x-transition.opacity>
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
                                @click.away="showFullCalendar = false">
                                <div class="flex justify-between items-center p-4 border-b border-gray-100 bg-gray-50">
                                    <h3 class="font-bold text-gray-900">Select Date</h3>
                                    <button @click="showFullCalendar = false"
                                        class="text-gray-400 hover:text-gray-600"><i
                                            class="fas fa-times text-xl"></i></button>
                                </div>
                                <div class="p-4 flex justify-center">
                                    <div id="full-calendar-container"></div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        {{-- Package Tabs --}}
                        <div class="grid grid-cols-3 border-b border-gray-200 bg-gray-50">
                            @if ($service->basic_price)
                                <button @click="switchPackage('basic')"
                                    :class="currentPackage === 'basic' ?
                                        'border-b-2 border-teal-600 text-teal-600 font-bold' : 'text-gray-500'"
                                    class="py-4 text-sm transition-all">Basic</button>
                            @endif
                            @if ($service->standard_price)
                                <button @click="switchPackage('standard')"
                                    :class="currentPackage === 'standard' ?
                                        'border-b-2 border-yellow-600 text-yellow-600 font-bold' : 'text-gray-500'"
                                    class="py-4 text-sm transition-all">Standard</button>
                            @endif
                            @if ($service->premium_price)
                                <button @click="switchPackage('premium')"
                                    :class="currentPackage === 'premium' ? 'border-b-2 border-red-600 text-red-600 font-bold' :
                                        'text-gray-500'"
                                    class="py-4 text-sm transition-all">Premium</button>
                            @endif
                        </div>

                        <div class="p-6">
                            {{-- Price Display --}}
                            <div class="flex justify-between items-end mb-6">
                                <span class="font-bold text-gray-400 text-sm uppercase">
                                    <span x-text="isSessionBased ? 'Total Price' : 'Task Price'"></span>
                                </span>
                                <span class="text-4xl font-extrabold text-indigo-600"
                                    x-text="'RM' + calculateTotal()"></span>
                            </div>

                            {{-- Duration Selector (HIDDEN IF NOT SESSION BASED) --}}
                            <div class="mb-6" x-show="isSessionBased">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase">Duration</label>
                                    <span class="text-xs text-gray-400" x-text="selectedDuration + ' Hours'"></span>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="h in [1, 2, 3, 4]" :key="h">
                                        <button @click="selectDuration(h)" type="button"
                                            class="py-2 rounded-lg border text-sm font-bold transition-all"
                                            :class="selectedDuration === h ? 'bg-indigo-600 text-white border-indigo-600' :
                                                'bg-white text-gray-600 border-gray-200'">
                                            <span x-text="h + 'h'"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <hr class="border-gray-100 mb-6">

                            {{-- Date Selection --}}
                            <div class="mb-6">
                                <label class="text-xs font-bold text-gray-700 uppercase mb-3 block">Select Day</label>
                                <div class="relative group">
                                    <button type="button"
                                        @click="$refs.dateScroller.scrollBy({ left: -200, behavior: 'smooth' })"
                                        class="absolute left-0 top-1/2 -translate-y-1/2 -ml-2 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md border border-gray-100 text-indigo-600 hover:bg-indigo-50 hover:scale-110 transition-all">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </button>

                                    <div x-ref="dateScroller"
                                        class="flex space-x-2 overflow-x-auto pb-4 px-1 no-scrollbar scroll-smooth">
                                        <template x-for="day in upcomingDays" :key="day.dateStr">
                                            <button @click="selectDate(day)" :disabled="!day.isAvailable"
                                                class="flex flex-col items-center justify-center min-w-[4.5rem] py-3 rounded-xl border transition-all flex-shrink-0 relative"
                                                :class="{
                                                    'bg-indigo-600 text-white border-indigo-600 shadow-md transform scale-105': selectedDate ===
                                                        day.dateStr,
                                                    'bg-white text-gray-700 hover:border-indigo-300': selectedDate !==
                                                        day.dateStr && day.isAvailable,
                                                    'bg-gray-50 text-gray-300 border-gray-100 cursor-not-allowed': !day
                                                        .isAvailable
                                                }">
                                                <span class="text-[10px] font-bold uppercase tracking-wider"
                                                    x-text="day.dayName"></span>
                                                <span class="text-xl font-extrabold mt-0.5"
                                                    x-text="day.dayNumber"></span>
                                                <span
                                                    x-show="new Date().toDateString() === new Date(day.dateStr).toDateString()"
                                                    class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full"
                                                    :class="selectedDate === day.dateStr ? 'bg-indigo-300' : 'bg-indigo-500'"></span>
                                            </button>
                                        </template>
                                    </div>

                                    <button type="button"
                                        @click="$refs.dateScroller.scrollBy({ left: 200, behavior: 'smooth' })"
                                        class="absolute right-0 top-1/2 -translate-y-1/2 -mr-2 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md border border-gray-100 text-indigo-600 hover:bg-indigo-50 hover:scale-110 transition-all">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Time Selection --}}
                            {{-- Time Selection --}}
                            <div x-show="selectedDate && isSessionBased" x-transition class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase">Start Time</label>
                                </div>
                            </div>

                            <div x-show="selectedDate && !isSessionBased" x-transition
                                class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700">
                                        <i class="fa-regular fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-indigo-900">Date Selected</p>
                                        <p class="text-xs text-indigo-700">This is a daily task request. No specific
                                            time required.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Book Button --}}
                            @auth
                                {{-- Button for Logged-in Users --}}
                                <button @click="submitBooking()"
                                    :disabled="!selectedDate || (isSessionBased && !selectedTime)"
                                    class="w-full py-3.5 rounded-xl font-bold text-white shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:bg-slate-300"
                                    :class="(!selectedDate || (isSessionBased && !selectedTime)) ? '' :
                                    'bg-indigo-600 hover:bg-indigo-700'">

                                    <span>Request Appointment</span>
                                    <i class="fa-solid fa-arrow-right text-sm"
                                        x-show="!(!selectedDate || (isSessionBased && !selectedTime))"></i>
                                </button>
                            @else
                                {{-- Button for Guests (Redirects to Login) --}}
                                <a href="{{ route('login') }}"
                                    class="w-full py-3.5 rounded-xl font-bold text-white shadow-md transition-all flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800">
                                    <span>Sign in to Request</span>
                                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Operating Hours & WhatsApp --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100">
                            <i class="fa-regular fa-clock text-gray-400"></i>
                            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wide">Weekly Hours</h3>
                        </div>
                        <div class="space-y-2">
                            @php
                                $daysMap = [
                                    'mon' => 'Monday',
                                    'tue' => 'Tuesday',
                                    'wed' => 'Wednesday',
                                    'thu' => 'Thursday',
                                    'fri' => 'Friday',
                                    'sat' => 'Saturday',
                                    'sun' => 'Sunday',
                                ];
                                $schedule = $service->operating_hours ?? [];
                            @endphp
                            @foreach ($daysMap as $key => $dayName)
                                @php
                                    $d = $schedule[$key] ?? [];
                                    $isOpen = isset($d['enabled']) && $d['enabled'] == true;
                                    $start = $d['start'] ?? '09:00';
                                    $end = $d['end'] ?? '17:00';
                                @endphp
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 w-20">{{ $dayName }}</span>
                                    @if ($isOpen)
                                        <span class="text-slate-700 font-medium">
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $start)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $end)->format('g:i A') }}
                                        </span>
                                    @else
                                        <span class="text-red-400 font-bold">Closed</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- WhatsApp Button --}}
                        <div class="mt-6 space-y-3">
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
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                    Chat on WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Helper Buttons (Share/Fav) --}}
                    <div class="flex items-center justify-center gap-4 py-2">
                        <button onclick="handleShare(this)" data-url="{{ route('services.details', $service->id) }}"
                            class="text-sm text-gray-500 hover:text-indigo-600 font-medium">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                        @php
                            $isFav = auth()->check() && $service->is_favourited;
                        @endphp

                        <button
                            onclick="handleFavourite({{ $service->id }}, {{ auth()->check() ? 'true' : 'false' }})"
                            class="text-sm font-medium flex items-center gap-1
        {{ $isFav ? 'text-red-500' : 'text-gray-500' }}">

                            <i id="heart-{{ $service->id }}" class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>

                            <span id="text-{{ $service->id }}">
                                {{ $isFav ? 'Saved' : 'Save' }}
                            </span>
                        </button>

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
                prices: {
                    basic: {{ $service->basic_price ?? 0 }},
                    standard: {{ $service->standard_price ?? 0 }},
                    premium: {{ $service->premium_price ?? 0 }}
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

                init() {
                    this.generateCalendar();
                },

                openCalendar() {
                    this.showFullCalendar = true;
                    this.$nextTick(() => {
                        if (!this.calendarInstance) {
                            this.initFullCalendar();
                        }
                    });
                },

                initFullCalendar() {
                    const container = document.getElementById("full-calendar-container");
                    if (!container) return;

                    this.calendarInstance = flatpickr("#full-calendar-container", {
                        inline: true,
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        disable: this.holidays,
                        locale: {
                            firstDayOfWeek: 1
                        },
                        onChange: (selectedDates, dateStr) => {
                            this.selectedDate = dateStr;
                            this.selectedTime = null;
                            const dateObj = new Date(dateStr);
                            const jsDays = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                            const dayKey = jsDays[dateObj.getDay()];
                            this.generateTimeSlots(dayKey);
                            this.showFullCalendar = false;
                        }
                    });
                },

                calculateEndTime(startTime) {
                    if (!startTime) return null;
                    let [h, m] = startTime.split(':').map(Number);
                    let currentMinutes = (h * 60) + m;
                    let endMinutes = currentMinutes + (this.selectedDuration * 60);

                    let endH = Math.floor(endMinutes / 60);
                    let endM = endMinutes % 60;

                    return `${endH.toString().padStart(2, '0')}:${endM.toString().padStart(2, '0')}`;
                },

                calculateTotal() {
                    return (this.prices[this.currentPackage] * this.selectedDuration).toFixed(2);
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

                generateTimeSlots(dayKey) {
                    this.timeSlots = [];
                    const config = this.schedule[dayKey];

                    if (!config || !config.enabled) return;

                    let [startH, startM] = config.start.split(':').map(Number);
                    let [endH, endM] = config.end.split(':').map(Number);

                    let currentMinutes = startH * 60 + startM;
                    let endMinutes = endH * 60 + endM;

                    let stepMinutes = this.sessionDuration;
                    let durationMinutes = this.selectedDuration * 60;

                    // Filter real bookings for this specific day
                    let daysBookings = this.bookedSlots.filter(slot => slot.date === this.selectedDate);

                    while (currentMinutes + durationMinutes <= endMinutes) {

                        let h = Math.floor(currentMinutes / 60);
                        let m = currentMinutes % 60;
                        let timeStr = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;

                        let proposedStart = currentMinutes;
                        let proposedEnd = currentMinutes + durationMinutes;

                        // 1. Check Real Bookings (Overlap Logic)
                        let isBooked = daysBookings.some(booking => {
                            let [bStartH, bStartM] = booking.start_time.split(':').map(Number);
                            let [bEndH, bEndM] = booking.end_time.split(':').map(Number);

                            let bookingStart = bStartH * 60 + bStartM;
                            let bookingEnd = bEndH * 60 + bEndM;

                            return (proposedStart < bookingEnd) && (proposedEnd > bookingStart);
                        });

                        // 🟢 2. Check Manual Blocks (Exact Match)
                        // The format in DB is "YYYY-MM-DD HH:MM"
                        let blockKey = `${this.selectedDate} ${timeStr}`;
                        let isManuallyBlocked = this.manualBlocks.includes(blockKey);

                        this.timeSlots.push({
                            time: timeStr,
                            available: !isBooked && !isManuallyBlocked // 🟢 Block if either is true
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
