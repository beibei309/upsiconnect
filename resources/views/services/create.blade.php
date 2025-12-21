@extends('layouts.helper')

{{-- 1. PHP Block to Initialize Defaults --}}
@php
    $defaultSchedule = [
        'mon' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
        'tue' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
        'wed' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
        'thu' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
        'fri' => ['enabled' => true, 'start' => '09:00', 'end' => '17:00'],
        'sat' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
        'sun' => ['enabled' => false, 'start' => '10:00', 'end' => '14:00'],
    ];
    // Use defaults for new service
    $scheduleData = $defaultSchedule;
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- 2. Libraries & Styles --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
{{-- Alpine JS is required for the Edit Blade logic --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Quill Customization */
    .ql-toolbar {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-color: #e5e7eb !important;
        background-color: #f9fafb;
    }

    .ql-container {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-color: #e5e7eb !important;
        font-family: inherit;
    }

    .ql-editor {
        min-height: 120px;
        font-size: 0.95rem;
    }

    /* Wizard Progress Line */
    .step-active {
        border-color: #4f46e5;
        color: #4f46e5;
    }

    .step-completed {
        border-color: #10b981;
        color: #10b981;
    }

    .step-inactive {
        border-color: transparent;
        color: #9ca3af;
    }

    /* --- COPIED STYLES FROM EDIT BLADE --- */
    [x-cloak] {
        display: none !important;
    }

    /* Toggle Switch */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #6366f1;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: #6366f1;
    }

    .toggle-checkbox {
        right: 0;
        z-index: 1;
        border-color: #cbd5e1;
        transition: all 0.3s;
    }

    .toggle-label {
        width: 100%;
        height: 100%;
        background-color: #cbd5e1;
        border-radius: 9999px;
        transition: background-color 0.3s;
    }
</style>

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Service</h1>
                    <p class="text-gray-600 mt-1">Fill in the details below to list your service.</p>
                </div>
                <a href="{{ route('services.manage') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Manage service
                </a>
            </div>

            <div class="mb-8 max-w-5xl mx-auto">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button
                            class="step-link step-active group w-1/4 py-4 px-1 border-b-2 font-medium text-sm flex items-center justify-center transition-colors"
                            data-target="overview">
                            <span
                                class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs mr-2 font-bold ring-1 ring-indigo-600">1</span>
                            Overview
                        </button>
                        <button
                            class="step-link step-inactive group w-1/4 py-4 px-1 border-b-2 font-medium text-sm flex items-center justify-center transition-colors pointer-events-none"
                            data-target="pricing">
                            <span
                                class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs mr-2 font-bold">2</span>
                            Pricing
                        </button>
                        <button
                            class="step-link step-inactive group w-1/4 py-4 px-1 border-b-2 font-medium text-sm flex items-center justify-center transition-colors pointer-events-none"
                            data-target="description">
                            <span
                                class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs mr-2 font-bold">3</span>
                            Description
                        </button>
                        <button
                            class="step-link step-inactive group w-1/4 py-4 px-1 border-b-2 font-medium text-sm flex items-center justify-center transition-colors pointer-events-none"
                            data-target="availability">
                            <span
                                class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs mr-2 font-bold">4</span>
                            Availability
                        </button>
                    </nav>
                </div>
            </div>

            <form id="createServiceForm" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative max-w-5xl mx-auto">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id ?? '' }}">

                <div id="overview" class="tab-section p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Service Basics</h2>
                        <p class="text-gray-500 text-sm">Let's start with the fundamental details.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 max-w-3xl">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Service Title <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="e.g. Professional Mathematics Tutoring">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category <span
                                    class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Service Cover Image</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                            <div class="mt-4">
                                <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Or select a
                                    template:</p>
                                <div class="flex gap-3 overflow-x-auto pb-2">
                                    <img src="/storage/service_tutor.jpg"
                                        class="template-image w-20 h-20 object-cover rounded-md border-2 border-gray-100 cursor-pointer hover:border-indigo-500 transition"
                                        data-val="/storage/service_tutor.jpg">
                                    <img src="/storage/programming_service.jpg"
                                        class="template-image w-20 h-20 object-cover rounded-md border-2 border-gray-100 cursor-pointer hover:border-indigo-500 transition"
                                        data-val="/storage/priya.jpg">
                                    <img src="/storage/design_service.jpg"
                                        class="template-image w-20 h-20 object-cover rounded-md border-2 border-gray-100 cursor-pointer hover:border-indigo-500 transition"
                                        data-val="/storage/design_service.jpg">
                                    <img src="/storage/laundry_service.jpg"
                                        class="template-image w-20 h-20 object-cover rounded-md border-2 border-gray-100 cursor-pointer hover:border-indigo-500 transition"
                                        data-val="/storage/laundry_service.jpg">
                                </div>
                                <input type="hidden" name="template_image" id="template_image">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="button" onclick="nextStep('overview', 'pricing')"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm flex items-center">
                            Next Step <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="pricing" class="tab-section hidden p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Packages & Pricing</h2>
                        <p class="text-gray-500 text-sm">Define your costs and what you offer.</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-6 mb-6 bg-gray-50 relative">
                        <span
                            class="absolute top-0 right-0 px-3 py-1 bg-gray-200 text-gray-600 text-xs font-bold rounded-bl-xl rounded-tr-xl">REQUIRED</span>
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <span class="w-3 h-3 bg-gray-800 rounded-full mr-2"></span> Basic Package
                        </h3>
                        <input type="hidden" name="packages[0][package_type]" value="basic">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Price (RM) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="basic_price" name="packages[0][price]"
                                    class="w-full mt-1 border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Duration (Display)</label>
                                <input type="text" name="packages[0][duration]" placeholder="e.g. 1 Hour"
                                    class="w-full mt-1 border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase">Frequency</label>
                                <select name="packages[0][frequency]" class="w-full mt-1 border-gray-300 rounded-md">
                                    <option value="Per Session">Per Session</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">What's included?</label>
                            <div class="bg-white rounded-md border border-gray-300 overflow-hidden">
                                <div id="editor-basic" class="h-24"></div>
                            </div>
                            <input type="hidden" name="packages[0][description]" id="input-basic">
                        </div>
                    </div>

                    <div class="flex items-center mb-6 bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                        <input type="checkbox" id="offer_packages" name="offer_packages"
                            class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                        <label for="offer_packages"
                            class="ml-3 block text-sm font-medium text-indigo-900 cursor-pointer select-none">
                            Offer <strong>Standard</strong> & <strong>Premium</strong> tiers
                        </label>
                    </div>

                    <div id="extraPackages" class="hidden space-y-6">
                        <div class="border border-blue-200 rounded-xl p-6 bg-blue-50/50">
                            <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-blue-600 rounded-full mr-2"></span> Standard Package
                            </h3>
                            <input type="hidden" name="packages[1][package_type]" value="standard">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div><label class="text-xs font-bold text-blue-600 uppercase">Price (RM)</label><input
                                        type="number" name="packages[1][price]"
                                        class="w-full mt-1 border-blue-200 rounded-md"></div>
                                <div><label class="text-xs font-bold text-blue-600 uppercase">Duration</label><input
                                        type="text" name="packages[1][duration]" placeholder="e.g. 2 Hours"
                                        class="w-full mt-1 border-blue-200 rounded-md"></div>
                                <div>
                                    <label class="text-xs font-bold text-blue-600 uppercase">Frequency</label>
                                    <select name="packages[1][frequency]" class="w-full mt-1 border-blue-200 rounded-md">
                                        <option value="Per Session">Per Session</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="bg-white rounded-md border border-blue-200 overflow-hidden">
                                <div id="editor-standard" class="h-20"></div>
                            </div>
                            <input type="hidden" name="packages[1][description]" id="input-standard">
                        </div>

                        <div class="border border-purple-200 rounded-xl p-6 bg-purple-50/50">
                            <h3 class="text-lg font-bold text-purple-800 mb-4 flex items-center">
                                <span class="w-3 h-3 bg-purple-600 rounded-full mr-2"></span> Premium Package
                            </h3>
                            <input type="hidden" name="packages[2][package_type]" value="premium">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div><label class="text-xs font-bold text-purple-600 uppercase">Price (RM)</label><input
                                        type="number" name="packages[2][price]"
                                        class="w-full mt-1 border-purple-200 rounded-md"></div>
                                <div><label class="text-xs font-bold text-purple-600 uppercase">Duration</label><input
                                        type="text" name="packages[2][duration]" placeholder="e.g. Full Day"
                                        class="w-full mt-1 border-purple-200 rounded-md"></div>
                                <div>
                                    <label class="text-xs font-bold text-purple-600 uppercase">Frequency</label>
                                    <select name="packages[2][frequency]"
                                        class="w-full mt-1 border-purple-200 rounded-md">
                                        <option value="Per Session">Per Session</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="bg-white rounded-md border border-purple-200 overflow-hidden">
                                <div id="editor-premium" class="h-20"></div>
                            </div>
                            <input type="hidden" name="packages[2][description]" id="input-premium">
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between">
                        <button type="button" onclick="nextStep('pricing', 'overview')"
                            class="px-5 py-2.5 text-gray-600 hover:text-gray-900 font-medium">
                            ← Back
                        </button>
                        <button type="button" onclick="nextStep('pricing', 'description')"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm flex items-center">
                            Next Step <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="description" class="tab-section hidden p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Detailed Description</h2>
                        <p class="text-gray-500 text-sm">Tell students why they should choose your service.</p>
                    </div>

                    <div class="mb-4">
                        <div class="bg-white rounded-lg border border-gray-300 overflow-hidden">
                            <div id="editor-main" class="h-64"></div>
                        </div>
                        <input type="hidden" name="description" id="input-main">
                        <p class="text-xs text-gray-400 mt-2 text-right">Be descriptive and professional.</p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between">
                        <button type="button" onclick="nextStep('description', 'pricing')"
                            class="px-5 py-2.5 text-gray-600 hover:text-gray-900 font-medium">
                            ← Back
                        </button>
                        <button type="button" onclick="nextStep('description', 'availability')"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm flex items-center">
                            Next Step <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="availability" class="tab-section hidden p-8">

                    {{-- Alpine Data Wrapper --}}
                    <div x-data="scheduleHandler()">

                        {{-- 🟢 NEW: Booking Type Toggle --}}
                        <div
                            class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-indigo-900">How is this service booked?</h3>
                                <p class="text-sm text-indigo-700 mt-1">
                                    <span x-show="isSessionBased"><strong>Appointment Based:</strong> Users book specific
                                        time slots (e.g. 1 hour tutoring).</span>
                                    <span x-show="!isSessionBased"><strong>Task Based:</strong> One-off requests (e.g.
                                        Laundry, Repair). No specific time duration required.</span>
                                </p>
                            </div>

                            {{-- Toggle Switch --}}
                            <div class="flex items-center bg-white rounded-lg p-1 border border-indigo-200 shadow-sm">
                                <button type="button" @click="isSessionBased = true"
                                    :class="isSessionBased ? 'bg-indigo-600 text-white shadow-sm' :
                                        'text-gray-500 hover:bg-gray-50'"
                                    class="px-4 py-2 rounded-md text-sm font-bold transition-all">
                                    Time Slots
                                </button>
                                <button type="button" @click="isSessionBased = false"
                                    :class="!isSessionBased ? 'bg-indigo-600 text-white shadow-sm' :
                                        'text-gray-500 hover:bg-gray-50'"
                                    class="px-4 py-2 rounded-md text-sm font-bold transition-all">
                                    One-off Task
                                </button>
                            </div>
                            {{-- Hidden input to send to backend (1 = Session, 0 = Task) --}}
                            <input type="hidden" name="is_session_based" :value="isSessionBased ? 1 : 0">
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                            <div class="lg:col-span-2 space-y-6">

                                {{-- 1. Session Duration (Wrapped in x-show) --}}
                                <div x-show="isSessionBased" x-transition.opacity.duration.300ms
                                    class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
                                    <div class="flex items-start gap-5 relative z-10">
                                        <div class="p-3.5 bg-indigo-50 rounded-2xl text-indigo-600 shadow-sm">
                                            <i class="fa-regular fa-hourglass-half text-2xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h2 class="font-bold text-slate-800 text-lg">Session Duration</h2>
                                            <p class="text-sm text-slate-500 mb-6 leading-relaxed">How long is one slot?
                                                (e.g., 9:00 - 9:30)</p>
                                            <div class="w-full max-w-xs relative">
                                                {{-- Disable input if hidden so it sends null --}}
                                                <select name="session_duration" :disabled="!isSessionBased"
                                                    class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 text-sm font-bold text-slate-700 appearance-none bg-slate-50">
                                                    @php
                                                        $options = [15, 20, 30, 45, 60, 90, 120];
                                                    @endphp
                                                    @foreach ($options as $opt)
                                                        <option value="{{ $opt }}"
                                                            {{ 60 == $opt ? 'selected' : '' }}>{{ $opt }} Minutes
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Weekly Schedule --}}
                                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                                    <div
                                        class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <div>
                                            <h2 class="font-bold text-slate-800">Weekly Availability</h2>
                                            <p class="text-xs text-slate-500 mt-1"
                                                x-text="isSessionBased ? 'Set your booking slots.' : 'Set when you are available to perform tasks.'">
                                            </p>
                                        </div>
                                        <button type="button" @click="showBulk = !showBulk"
                                            class="text-sm text-indigo-600 font-bold hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg transition-colors"><i
                                                class="fa-solid fa-sliders mr-1.5"></i> Bulk Edit</button>
                                    </div>

                                    {{-- Bulk Edit Panel --}}
                                    <div x-show="showBulk" x-transition
                                        class="bg-indigo-50/80 backdrop-blur-sm p-4 border-b border-indigo-100 flex items-center gap-4">
                                        <span class="text-xs font-bold text-indigo-800 uppercase">Set all to:</span>
                                        <div
                                            class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-indigo-100 shadow-sm">
                                            <input type="time" x-model="bulkStart"
                                                class="border-none p-0 text-xs font-bold text-slate-700 focus:ring-0">
                                            <span class="text-slate-300 text-xs">→</span>
                                            <input type="time" x-model="bulkEnd"
                                                class="border-none p-0 text-xs font-bold text-slate-700 focus:ring-0">
                                        </div>
                                        <button type="button" @click="applyBulkTime()"
                                            class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md">Apply</button>
                                    </div>

                                    <div class="divide-y divide-slate-100">
                                        <template x-for="day in days" :key="day.key">
                                            <div
                                                class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors group">
                                                <div class="flex items-center gap-5 w-48">
                                                    <div class="relative inline-block w-12 h-7 align-middle select-none">
                                                        <input type="checkbox"
                                                            :name="`operating_hours[${day.key}][enabled]`"
                                                            :id="`toggle-${day.key}`" x-model="schedule[day.key].enabled"
                                                            value="1"
                                                            class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-200 checked:right-0 checked:border-indigo-600 transition-all duration-300 shadow-sm" />
                                                        <label :for="`toggle-${day.key}`"
                                                            class="toggle-label block overflow-hidden h-7 rounded-full bg-slate-200 cursor-pointer"></label>
                                                    </div>
                                                    <label :for="`toggle-${day.key}`"
                                                        class="text-sm font-bold text-slate-700 cursor-pointer select-none group-hover:text-indigo-700 transition-colors"
                                                        x-text="day.name"></label>
                                                </div>
                                                <div class="flex-1 flex justify-end">
                                                    <div x-show="schedule[day.key].enabled"
                                                        class="flex items-center gap-3">
                                                        <div
                                                            class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-sm group-hover:border-indigo-200 transition-colors">
                                                            <input type="time"
                                                                :name="`operating_hours[${day.key}][start]`"
                                                                x-model="schedule[day.key].start"
                                                                class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0">
                                                            <span class="text-slate-300 text-xs px-2 font-light">to</span>
                                                            <input type="time"
                                                                :name="`operating_hours[${day.key}][end]`"
                                                                x-model="schedule[day.key].end"
                                                                class="border-none p-0 text-sm font-bold text-slate-700 focus:ring-0">
                                                        </div>
                                                    </div>
                                                    <div x-show="!schedule[day.key].enabled"
                                                        class="text-xs font-bold text-slate-400 py-2 px-5 bg-slate-100 rounded-lg uppercase tracking-wider">
                                                        Closed</div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-1 space-y-6">
                                {{-- 3. Block Dates --}}
                                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-24">
                                    <h2 class="font-bold text-slate-800 mb-2">Block Dates</h2>
                                    <p class="text-xs text-slate-500 mb-6 leading-relaxed">Select specific dates (like
                                        holidays) when you are unavailable.</p>
                                    <div class="relative mb-6">
                                        <input type="text" id="unavailableDates" name="unavailable_dates"
                                            class="w-full pl-10 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3 font-medium shadow-sm"
                                            placeholder="Select dates...">
                                        <i class="fa-regular fa-calendar absolute left-3.5 top-3.5 text-slate-400"></i>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <button type="button" onclick="quickBlockDates(1, 'week')"
                                                class="px-3 py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 text-xs rounded-lg font-bold transition border border-slate-200">+
                                                1 Week</button>
                                            <button type="button" onclick="quickBlockDates(1, 'month')"
                                                class="px-3 py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 text-xs rounded-lg font-bold transition border border-slate-200">+
                                                1 Month</button>
                                        </div>
                                        <button type="button" onclick="clearUnavailableDates()"
                                            class="w-full px-3 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs rounded-lg font-bold transition border border-rose-100 hover:border-rose-200 flex items-center justify-center gap-2"><i
                                                class="fa-solid fa-trash-can"></i> Clear All</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100 mt-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Ready to Launch?</h3>
                    <p class="text-gray-600 mb-6 max-w-sm mx-auto">Your service will be visible to students immediately
                        after publishing.</p>

                    <div class="flex justify-center gap-4">
                        <button type="button" onclick="nextStep('availability', 'description')"
                            class="px-5 py-3 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition">
                            Review Details
                        </button>
                        <button type="button" onclick="submitForm()"
                            class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg transform hover:-translate-y-0.5 flex items-center">
                            Publish Service Now
                        </button>
                    </div>
                </div>
        </div>

        </form>
    </div>
    </div>

    <script>
        // --- 1. QUILL CONFIG ---
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],
            [{
                'list': 'bullet'
            }]
        ];

        function setupQuill(editorId, inputId, placeholder) {
            var quill = new Quill('#' + editorId, {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: placeholder
            });
            quill.on('text-change', function() {
                document.getElementById(inputId).value = quill.root.innerHTML;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupQuill('editor-basic', 'input-basic', 'e.g. 1 hour online consultation...');
            setupQuill('editor-standard', 'input-standard', 'Describe standard package...');
            setupQuill('editor-premium', 'input-premium', 'Describe premium package...');
            setupQuill('editor-main', 'input-main', 'Provide a comprehensive description of your service...');
        });

        // --- 2. ALPINE JS SCHEDULER (Copied from Edit) ---
        // --- 2. ALPINE JS SCHEDULER ---
        function scheduleHandler() {
            return {
                // 🟢 NEW: Initialize Session Based as True (Default for Create Page)
                isSessionBased: true,

                days: [{
                        key: 'mon',
                        name: 'Monday'
                    }, {
                        key: 'tue',
                        name: 'Tuesday'
                    }, {
                        key: 'wed',
                        name: 'Wednesday'
                    },
                    {
                        key: 'thu',
                        name: 'Thursday'
                    }, {
                        key: 'fri',
                        name: 'Friday'
                    }, {
                        key: 'sat',
                        name: 'Saturday'
                    }, {
                        key: 'sun',
                        name: 'Sunday'
                    }
                ],
                schedule: @json($scheduleData),
                showBulk: false,
                bulkStart: '09:00',
                bulkEnd: '17:00',
                applyBulkTime() {
                    for (const dayKey in this.schedule) {
                        if (this.schedule[dayKey].enabled) {
                            this.schedule[dayKey].start = this.bulkStart;
                            this.schedule[dayKey].end = this.bulkEnd;
                        }
                    }
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    }).fire({
                        icon: 'success',
                        title: 'Schedule Updated'
                    });
                    this.showBulk = false;
                }
            }
        }

        // --- 3. FLATPICKR LOGIC (Copied from Edit) ---
        let fpInstance;
        document.addEventListener('DOMContentLoaded', function() {
            fpInstance = flatpickr("#unavailableDates", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                minDate: "today",
                conjunction: ", ",
                locale: {
                    firstDayOfWeek: 1
                }
            });
        });

        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }
        window.quickBlockDates = function(amount, unit) {
            if (!fpInstance) return;
            let daysToAdd = unit === 'week' ? amount * 7 : amount * 30,
                newDates = [],
                today = new Date();
            for (let i = 0; i < daysToAdd; i++) {
                let d = new Date(today);
                d.setDate(today.getDate() + i);
                newDates.push(formatDate(d));
            }
            let current = fpInstance.selectedDates.map(d => formatDate(d));
            fpInstance.setDate([...new Set([...current, ...newDates])], true);
        };
        window.clearUnavailableDates = function() {
            if (fpInstance) fpInstance.clear();
        };


        // --- 4. TEMPLATE IMAGE LOGIC ---
        document.querySelectorAll('.template-image').forEach(img => {
            img.addEventListener('click', function() {
                document.querySelectorAll('.template-image').forEach(i => i.classList.remove('ring-4',
                    'ring-indigo-300'));
                this.classList.add('ring-4', 'ring-indigo-300');
                document.getElementById('template_image').value = this.dataset.val;
                document.getElementById('image').value = "";
            });
        });

        // --- 5. EXTRA PACKAGES TOGGLE ---
        document.getElementById('offer_packages').addEventListener('change', function() {
            const extra = document.getElementById('extraPackages');
            if (this.checked) {
                extra.classList.remove('hidden');
            } else {
                extra.classList.add('hidden');
            }
        });

        // --- 6. NAVIGATION LOGIC ---
        function nextStep(currentId, nextId) {
            // Validation Logic
            if (nextId !== 'overview' && nextId !== 'pricing' && nextId !== 'description') {
                if (currentId === 'overview' && nextId === 'pricing') {
                    if (!document.getElementById('title').value || !document.getElementById('category_id').value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required Fields',
                            text: 'Please provide a Title and Category.'
                        });
                        return;
                    }
                }
                if (currentId === 'pricing' && nextId === 'description') {
                    if (!document.getElementById('basic_price').value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required Fields',
                            text: 'Please set a price for the Basic Package.'
                        });
                        return;
                    }
                }
                if (currentId === 'description' && nextId === 'availability') {
                    const desc = document.getElementById('input-main').value;
                    if (!desc || desc === '<p><br></p>') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required Fields',
                            text: 'Please write a description for your service.'
                        });
                        return;
                    }
                }
            }

            // Hide all sections
            document.querySelectorAll('.tab-section').forEach(el => el.classList.add('hidden'));
            // Show target
            document.getElementById(nextId).classList.remove('hidden');

            // Update Headers
            updateHeader(nextId);
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateHeader(activeId) {
            const map = {
                'overview': 0,
                'pricing': 1,
                'description': 2,
                'availability': 3
            };
            const activeIndex = map[activeId];
            const links = document.querySelectorAll('.step-link');

            links.forEach((link, index) => {
                link.className =
                    "step-link w-1/4 py-4 px-1 border-b-2 font-medium text-sm flex items-center justify-center transition-colors pointer-events-none";
                const circle = link.querySelector('span');

                if (index < activeIndex) {
                    link.classList.add('step-completed', 'border-green-500', 'text-green-600');
                    circle.className =
                        "w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs mr-2 font-bold";
                    circle.innerHTML = "✓";
                } else if (index === activeIndex) {
                    link.classList.add('step-active', 'border-indigo-500', 'text-indigo-600');
                    circle.className =
                        "w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs mr-2 font-bold ring-1 ring-indigo-600";
                    circle.innerHTML = index + 1;
                } else {
                    link.classList.add('step-inactive', 'border-transparent', 'text-gray-400');
                    circle.className =
                        "w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs mr-2 font-bold";
                    circle.innerHTML = index + 1;
                }
            });
        }

        // --- 7. FINAL SUBMISSION ---
        async function submitForm() {
            const form = document.getElementById('createServiceForm');
            // Ensure Flatpickr value is synced
            if (fpInstance) document.getElementById('unavailableDates').value = fpInstance.input.value;

            const formData = new FormData(form);

            Swal.fire({
                title: 'Publishing Service',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch("{{ route('services.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submission Successful',
                        text: 'Your service has been submitted to admin for approval.',
                        confirmButtonText: 'Go to Dashboard',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.href = "{{ route('services.manage') }}";
                    });
                } else {
                    Swal.fire('Error', data.error || 'Something went wrong.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('System Error', 'Please check your connection.', 'error');
            }
        }
    </script>
@endsection
