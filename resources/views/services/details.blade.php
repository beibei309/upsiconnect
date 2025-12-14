<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $service->title ?? 'Service Page' }} - S2U</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* Slate-50 */
        }

        h1,
        h2,
        h3,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sticky Sidebar logic */
        @media (min-width: 1024px) {
            .sticky-sidebar {
                position: sticky;
                top: 100px;
                /* Adjust based on navbar height */
            }
        }

        .tab-button {
            transition: all 0.2s ease-in-out;
        }

        .rich-text ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            /* Space for bullet */
            margin-bottom: 0.5rem;
        }

        .rich-text ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .rich-text p {
            margin-bottom: 0.5rem;
        }

        .rich-text strong {
            font-weight: 600;
            color: #1e293b;
        }
    </style>
</head>

<body class="antialiased text-slate-800">

    @include('layouts.navbar')

    <div class="bg-white border-b border-gray-200 pt-24 pb-6">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center hover:text-indigo-600 transition-colors">
                            <i class="fa-solid fa-house mr-2"></i> Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                            <a href="{{ route('services.index') }}" class="hover:text-indigo-600 transition-colors">Find
                                Services</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                            <span
                                class="font-medium text-gray-800 truncate max-w-[200px] md:max-w-md">{{ $service->title ?? 'Service Details' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <div class="lg:col-span-8 space-y-8">

                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 leading-tight mb-4">
                        {{ $service->title ?? 'Service Title' }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <a href="{{ auth()->guest() ? route('login') : route('students.profile', $service->user) }}"
                            class="flex items-center gap-2 group">
                            @if ($service->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                    class="w-8 h-8 rounded-full object-cover ring-2 ring-white shadow-sm group-hover:ring-indigo-100 transition {{ auth()->guest() ? 'blur-sm' : '' }}">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-white shadow-sm {{ auth()->guest() ? 'blur-sm' : '' }}">
                                    {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                </div>
                            @endif

                            <span class="font-semibold text-slate-900 group-hover:text-indigo-600 transition">
                                {{ $service->user->name }}
                            </span>
                        </a>

                        <span class="text-gray-300">|</span>

                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-bold text-slate-900">{{ $service->rating ?? '0.0' }}</span>
                            <span class="text-slate-500">({{ $service->user->reviewsReceived()->count() }}
                                reviews)</span>
                        </div>

                        @if ($service->user->trust_badge)
                            <span
                                class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold border border-blue-100 flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-100 bg-white">
                    <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                        alt="Service image"
                        class="w-full h-[400px] object-cover hover:scale-105 transition-transform duration-700">
                </div>

                <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 border-b border-gray-100 pb-2">About This Service
                    </h2>
                    <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed">
                        {!! $service->description !!}
                    </div>
                </section>

                <section
                    class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                    @php $isGuest = !auth()->check(); @endphp

                    @if ($isGuest)
                        <div
                            class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                            <a href="{{ route('login') }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full font-semibold shadow-lg transform hover:-translate-y-1 transition-all">
                                Sign in to view Provider Details
                            </a>
                        </div>
                    @endif

                    <div class="{{ $isGuest ? 'filter blur-sm select-none' : '' }}">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">About The Helper</h2>

                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="flex-shrink-0">
                                @if ($service->user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $service->user->profile_photo_path) }}"
                                        class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                                @else
                                    <div
                                        class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-3xl text-white font-bold shadow-sm">
                                        {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 space-y-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ $service->user->name }}</h3>
                                    <p class="text-slate-500">{{ $service->user->faculty ?? 'Faculty Student' }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-400 block text-xs">From</span>
                                        <span class="font-medium text-slate-700">Malaysia</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-xs">Member since</span>
                                        <span
                                            class="font-medium text-slate-700">{{ $service->created_at->format('M Y') }}</span>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-100">
                                    <p class="text-gray-600 italic">
                                        "{{ $service->user->bio ?? 'Ready to help you with your tasks!' }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-bold text-slate-900">Reviews</h2>
                        <div class="flex items-center gap-2 bg-yellow-50 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span
                                class="font-bold text-slate-800">{{ round($service->user->reviewsReceived()->avg('rating'), 1) ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @forelse ($service->user->reviewsReceived as $review)
                            <div class="border-b border-gray-100 pb-8 last:border-0 last:pb-0">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                                            @if (optional($review->reviewer)->profile_photo_path)
                                                <img src="{{ asset('storage/' . $review->reviewer->profile_photo_path) }}"
                                                    class="w-full h-full object-cover {{ auth()->guest() ? 'blur-sm' : '' }}">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100 text-xs font-bold {{ auth()->guest() ? 'blur-sm' : '' }}">
                                                    {{ substr(optional($review->reviewer)->name ?? 'A', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-slate-900 text-sm">
                                                    @auth
                                                        {{ optional($review->reviewer)->name ?? 'Anonymous User' }}
                                                    @else
                                                        {{ Str::mask(optional($review->reviewer)->name ?? 'Anonymous User', '*', 3) }}
                                                    @endauth
                                                </h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <div class="flex text-yellow-400 text-xs">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs text-gray-400">•
                                                        {{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm mt-3 leading-relaxed">{{ $review->comment }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400">
                                <p>No reviews yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>
            <div class="lg:col-span-4">
                <div class="sticky-sidebar space-y-4">

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="grid grid-cols-3 border-b border-gray-200 bg-gray-50">
                            @if ($service->basic_price)
                                <button
                                    class="tab-button py-4 text-sm font-semibold text-gray-500 hover:text-gray-800 focus:outline-none"
                                    data-tab="basic">Basic</button>
                            @endif
                            @if ($service->standard_price)
                                <button
                                    class="tab-button py-4 text-sm font-semibold text-gray-500 hover:text-gray-800 focus:outline-none"
                                    data-tab="standard">Standard</button>
                            @endif
                            @if ($service->premium_price)
                                <button
                                    class="tab-button py-4 text-sm font-semibold text-gray-500 hover:text-gray-800 focus:outline-none"
                                    data-tab="premium">Premium</button>
                            @endif
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-end mb-6">
                                <span class="font-bold text-gray-400 text-sm mb-1 uppercase tracking-wider">Total
                                    Price</span>
                                <span id="main-price-display"
                                    class="text-4xl font-extrabold transition-colors duration-300">
                                    RM{{ number_format($service->basic_price ?? 0, 0) }}
                                </span>
                            </div>

                            <div id="tab-content" class="min-h-[100px]">
                                @if ($service->basic_price)
                                    <div id="basic" class="tab-content hidden animate-fade-in">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="font-bold text-slate-800">Basic Package</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
                                                {{ $service->basic_duration }} hrs per {{ $service->basic_frequency }}
                                            </span>
                                        </div>
                                        <div class="rich-text text-sm text-gray-600 leading-relaxed">
                                            {!! $service->basic_description !!}
                                        </div>
                                    </div>
                                @endif

                                @if ($service->standard_price)
                                    <div id="standard" class="tab-content hidden animate-fade-in">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="font-bold text-slate-800">Standard Package</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
                                                {{ $service->standard_duration }} hrs per
                                                {{ $service->standard_frequency }}
                                            </span>
                                        </div>
                                        <div class="rich-text text-sm text-gray-600 leading-relaxed">
                                            {!! $service->standard_description !!}
                                        </div>
                                    </div>
                                @endif

                                @if ($service->premium_price)
                                    <div id="premium" class="tab-content hidden animate-fade-in">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="font-bold text-slate-800">Premium Package</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
                                                {{ $service->premium_duration }} hrs per
                                                {{ $service->premium_frequency }}
                                            </span>
                                        </div>
                                        <div class="rich-text text-sm text-gray-600 leading-relaxed">
                                            {!! $service->premium_description !!}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-8">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Select Date</label>
                                <div class="relative">
                                    <input type="text" id="calendar"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Check availability..." />
                                    <i class="fas fa-calendar absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                <div id="availability-status" class="mt-2 text-xs font-semibold h-4"></div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @php
                                    $helperIsAvailable = $service->user->is_available ?? false;

                                    $mainBtnClasses =
                                        'w-full py-3.5 rounded-xl font-bold transition-all shadow-md flex items-center justify-center';

                                    if ($helperIsAvailable) {
                                        $mainBtnClasses .=
                                            ' bg-slate-900 hover:bg-indigo-600 text-white hover:shadow-lg transform hover:-translate-y-0.5';
                                        $mainBtnText =
                                            'Continue (RM<span id="btn-price">' .
                                            number_format($service->basic_price ?? 0, 0) .
                                            '</span>)';
                                    } else {
                                        $mainBtnClasses .= ' bg-red-600 text-white opacity-90 cursor-not-allowed';
                                        $mainBtnText = 'Currently Busy / Unavailable';
                                    }

                                    $rawPhone = $service->user->phone_number ?? ($service->user->phone ?? '');

                                    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);

                                    if (substr($cleanPhone, 0, 1) === '0') {
                                        $cleanPhone = '60' . substr($cleanPhone, 1);
                                    }

                                    $whatsappUrl =
                                        "https://wa.me/{$cleanPhone}?text=Hi, I am interested in your service: " .
                                        urlencode($service->title);
                                @endphp

                                @auth
                                    <button type="button" id="request-service-btn"
                                        data-is-available="{{ $helperIsAvailable ? 'true' : 'false' }}"
                                        {{ !$helperIsAvailable ? 'disabled' : '' }} class="{{ $mainBtnClasses }}">
                                        {!! $mainBtnText !!}
                                    </button>

                                    @if (!empty($cleanPhone))
                                        <a href="{{ $whatsappUrl }}" target="_blank"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                            </svg>
                                            Chat on WhatsApp
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full text-center bg-slate-900 hover:bg-indigo-600 text-white py-3.5 rounded-xl font-bold transition-all shadow-md">
                                        Sign in to Request
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-4 py-2">
                        <button onclick="handleShare(this)" data-url="{{ route('services.details', $service->id) }}"
                            class="flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 font-medium transition">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                        <button
                            onclick="handleFavourite({{ $service->id }}, {{ auth()->check() ? 'true' : 'false' }})"
                            class="flex items-center gap-2 text-sm text-gray-500 hover:text-red-500 font-medium transition">
                            <i id="heart-{{ $service->id }}" class="far fa-heart"></i> Save
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>

    @include('layouts.footer')

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
            <p id="copyMessage" class="text-xs text-green-600 mt-2 text-center opacity-0 transition-opacity">Copied to
                clipboard!</p>
        </div>
    </div>

    @auth
        <div id="requestServiceModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-2xl w-full max-w-md shadow-2xl animate-fade-in-up">
                <h2 class="text-xl font-bold mb-1 text-slate-900">Request Details</h2>
                <p class="text-sm text-gray-500 mb-6">Confirm your request details below.</p>

                <div class="bg-gray-50 p-4 rounded-xl mb-4 border border-gray-100">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Date:</span>
                        <span id="selected-date-display" class="font-bold text-slate-900"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Package:</span>
                        <span id="selected-package-display" class="font-bold text-slate-900 capitalize"></span>
                    </div>
                </div>

                <textarea id="service-message"
                    class="w-full p-3 border border-gray-300 rounded-xl mb-4 text-sm focus:ring-2 focus:ring-indigo-500 outline-none"
                    rows="3" placeholder="Add a note for the helper (optional)..."></textarea>

                <div class="flex gap-3">
                    <button id="close-modal"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition">Cancel</button>
                    <button id="submit-service-request"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-xl hover:bg-indigo-700 font-bold shadow-md transition">Send
                        Request</button>
                </div>
            </div>
        </div>
    @endauth

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Data passed from PHP
            const unavailableDates = @json($service->unavailable_dates ? json_decode($service->unavailable_dates) : []);

            // Prices configuration
            const prices = {
                basic: {{ $service->basic_price ?? 0 }},
                standard: {{ $service->standard_price ?? 0 }},
                premium: {{ $service->premium_price ?? 0 }}
            };

            // Colors configuration (Tailwind text colors / Hex)
            const colors = {
                basic: '#0d9488', // Teal-600
                standard: '#ca8a04', // Yellow-600
                premium: '#dc2626' // Red-600
            };

            // DOM Elements
            const tabs = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const mainPriceDisplay = document.getElementById('main-price-display');
            const btnPriceDisplay = document.getElementById('btn-price');
            const statusDiv = document.getElementById("availability-status");
            let selectedDate = null;
            let currentPackage = 'basic'; // Default

            // --- 1. Tab Switching & Price Updating Logic ---
            function switchTab(pkg) {
                currentPackage = pkg;

                // Hide/Show content logic (Sama macam sebelum ini)
                tabContents.forEach(content => content.classList.add('hidden'));
                const contentToShow = document.getElementById(pkg);
                if (contentToShow) contentToShow.classList.remove('hidden');

                // --- LOGIC WARNA TAB DI SINI ---
                tabs.forEach(t => {
                    // Reset semua tab ke warna kelabu (default)
                    t.classList.remove('border-b-2');
                    t.style.borderColor = 'transparent';
                    t.style.color = '#6b7280'; // text-gray-500
                    t.style.fontWeight = 'normal';

                    // Jika tab ini adalah tab yang dipilih (Active)
                    if (t.dataset.tab === pkg) {
                        t.classList.add('border-b-2');
                        t.style.fontWeight = '700'; // Bold

                        // Guna warna dari object 'colors' di atas
                        t.style.color = colors[pkg];
                        t.style.borderColor = colors[pkg];
                    }
                });

                // Update warna harga besar (Total Price)
                mainPriceDisplay.textContent = 'RM' + prices[pkg];
                mainPriceDisplay.style.color = colors[pkg];

                // Update button text
                if (btnPriceDisplay) btnPriceDisplay.textContent = prices[pkg];
            }

            // Initialize Tabs
            tabs.forEach(tab => {
                tab.addEventListener('click', () => switchTab(tab.dataset.tab));
            });

            // Set Initial Tab
            @if ($service->basic_price)
                switchTab('basic');
            @elseif ($service->standard_price) switchTab('standard');
            @elseif ($service->premium_price) switchTab('premium');
            @endif


            // --- 2. Calendar Logic (Flatpickr) ---
            flatpickr("#calendar", {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: unavailableDates,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    let date = dayElem.dateObj.toISOString().split("T")[0];
                    if (unavailableDates.includes(date)) {
                        dayElem.classList.add("bg-red-50", "text-red-400", "cursor-not-allowed");
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    selectedDate = dateStr;
                    if (!dateStr) {
                        statusDiv.textContent = "";
                        return;
                    }
                    if (unavailableDates.includes(dateStr)) {
                        statusDiv.textContent = "Unavailable";
                        statusDiv.className = "mt-2 text-xs font-bold text-red-500";
                    } else {
                        statusDiv.textContent = "Available";
                        statusDiv.className = "mt-2 text-xs font-bold text-green-600";
                    }
                }
            });

            @auth
            const modal = document.getElementById("requestServiceModal");
            const reqBtn = document.getElementById("request-service-btn");
            const closeBtn = document.getElementById("close-modal");
            const submitBtn = document.getElementById("submit-service-request");

            reqBtn.addEventListener("click", () => {

                // Ambil status ketersediaan umum dari atribut data-is-available (yang diset di Blade)
                const helperIsGenerallyAvailable = reqBtn.getAttribute('data-is-available') === 'true';

                // 🛑 CHECK 1: Ketersediaan Umum Helper (jika tombol tidak didisable oleh browser, tapi statusnya false)
                if (!helperIsGenerallyAvailable) {
                    Swal.fire({
                        icon: "warning",
                        title: "Currently Busy",
                        text: "The student has set their overall status to 'Busy' and cannot receive new orders. Please check back later or choose another helper.",
                        confirmButtonColor: '#4f46e5'
                    });
                    return;
                }

                // 🛑 CHECK 2: Tanggal dipilih
                if (!selectedDate) {
                    Swal.fire({
                        icon: "warning",
                        title: "Select a date",
                        text: "Please select a date for your service request.",
                        confirmButtonColor: '#334155'
                    });
                    return;
                }

                // 🛑 CHECK 3: Tanggal yang dipilih tidak tersedia (berdasarkan Flatpickr/unavailableDates)
                if (unavailableDates.includes(selectedDate)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Unavailable Date",
                        text: "The student is scheduled to be unavailable on " + selectedDate +
                            ". Please choose another date.",
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                // Jika semua check lolos, tampilkan modal request
                document.getElementById("selected-date-display").textContent = selectedDate;
                document.getElementById("selected-package-display").textContent = currentPackage;
                modal.classList.remove("hidden");
            });

            closeBtn.addEventListener("click", () => modal.classList.add("hidden"));

            // Logik submitBtn (gunakan error handling yang lebih baik untuk pesan spesifik)
            submitBtn.addEventListener("click", () => {
                const message = document.getElementById("service-message").value;
                const offeredPrice = prices[currentPackage];

                fetch("{{ route('service-requests.store') }}", {
                        // ... (data dan headers)
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            student_service_id: {{ $service->id }},
                            selected_dates: selectedDate,
                            selected_package: currentPackage,
                            message: message,
                            offered_price: offeredPrice
                        })
                    })
                    .then(res => {
                        // Jika status code bukan 200-299, throw error untuk ditangkap di catch
                        if (!res.ok) {
                            // Coba parse JSON untuk mendapatkan pesan error dari server
                            return res.json().then(errorData => {
                                // Gunakan server message jika ada, atau default message
                                throw new Error(errorData.message ||
                                    "Failed to process request.");
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: "success",
                            title: "Sent!",
                            text: data.message,
                            confirmButtonColor: '#4f46e5'
                        });
                        modal.classList.add("hidden");
                    })
                    .catch(err => {
                        console.error('Order Submission Failure:', err);

                        // Tampilkan pesan error spesifik dari server (jika ada) atau pesan default
                        Swal.fire({
                            icon: "error",
                            title: "Request Failed",
                            text: err.message ||
                                "Could not submit your request due to a server error.",
                            confirmButtonColor: '#ef4444'
                        });
                    });
            });
        @endauth
        });

        // --- 4. Share Logic ---
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

        // --- 5. Favorite Logic ---
        function handleFavourite(id, loggedIn) {
            if (!loggedIn) return window.location.href = "{{ route('login') }}";
            const icon = document.getElementById('heart-' + id);
            const isSaved = icon.classList.contains('fas'); // Solid

            // Optimistic UI
            icon.className = isSaved ? 'far fa-heart' : 'fas fa-heart text-red-500';

            fetch('/favourites/toggle/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    service_id: id
                })
            });
        }
    </script>
</body>

</html>
