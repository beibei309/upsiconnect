@extends('layouts.helper')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        .ql-toolbar {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-color: #d1d5db !important;
            background-color: #f9fafb;
        }

        .ql-container {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border-color: #d1d5db !important;
            font-family: inherit;
            background-color: white;
        }

        .ql-editor {
            min-height: 120px;
            font-size: 0.875rem;
        }
    </style>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <br><br><br>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Service</h1>
                    <p class="mt-2 text-sm text-gray-600">Update the details of your service offering.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('services.manage') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Manage
                    </a>
                </div>
            </div>

            <form id="serviceForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 space-y-8">

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Basic Information</h3>
                                <p class="mt-1 text-sm text-gray-500">General details about your service.</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Service Title
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="title" value="{{ $service->title }}"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 border">
                                </div>

                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span
                                            class="text-red-500">*</span></label>
                                    <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 border bg-white">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span
                                            class="text-red-500">*</span></label>

                                    <div id="main-editor-container" style="height: 200px;">
                                        {!! $service->description !!}
                                    </div>

                                    <input type="hidden" name="description" id="hidden-description">

                                    <p class="mt-2 text-sm text-gray-500">Briefly describe what your service entails.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Pricing Packages</h3>
                                    <p class="mt-1 text-sm text-gray-500">Define your service tiers.</p>
                                </div>
                                @php
                                    $offerPackages = $service->standard_price || $service->premium_price;
                                @endphp
                                <div class="flex items-center">
                                    <input type="checkbox" id="togglePackages" name="offer_packages"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        {{ $offerPackages ? 'checked' : '' }}>
                                    <label for="togglePackages" class="ml-2 block text-sm text-gray-900">Enable
                                        Tiers</label>
                                </div>
                            </div>

                            <div class="p-6">
                                @php
                                    $packages = [
                                        [
                                            'name' => 'Basic',
                                            'key' => 'basic',
                                            'color' => 'bg-gray-100 text-gray-800',
                                            'duration' => $service->basic_duration,
                                            'frequency' => $service->basic_frequency,
                                            'price' => $service->basic_price,
                                            'description' => $service->basic_description,
                                        ],
                                        [
                                            'name' => 'Standard',
                                            'key' => 'standard',
                                            'color' => 'bg-blue-50 text-blue-800',
                                            'duration' => $service->standard_duration,
                                            'frequency' => $service->standard_frequency,
                                            'price' => $service->standard_price,
                                            'description' => $service->standard_description,
                                        ],
                                        [
                                            'name' => 'Premium',
                                            'key' => 'premium',
                                            'color' => 'bg-indigo-50 text-indigo-800',
                                            'duration' => $service->premium_duration,
                                            'frequency' => $service->premium_frequency,
                                            'price' => $service->premium_price,
                                            'description' => $service->premium_description,
                                        ],
                                    ];
                                @endphp

                                <div class="space-y-8">
                                    @foreach ($packages as $i => $pkg)
                                        <div class="package-section {{ $i > 0 ? 'extra-package border-t pt-6 mt-6' : '' }}"
                                            style="{{ $i > 0 && !$offerPackages ? 'display:none;' : '' }}">

                                            <div class="flex items-center mb-4">
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pkg['color'] }}">
                                                    {{ $pkg['name'] }}
                                                </span>
                                                <h4 class="ml-2 text-md font-semibold text-gray-900">{{ $pkg['name'] }}
                                                    Package</h4>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                                <div class="col-span-1 md:col-span-2">
                                                    <label
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Description</label>

                                                    <div id="pkg-editor-{{ $i }}" class="bg-white"
                                                        style="height: 100px;">
                                                        {!! $pkg['description'] !!}
                                                    </div>

                                                    <input type="hidden" name="packages[{{ $i }}][description]"
                                                        id="hidden-pkg-{{ $i }}">
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Price
                                                        (RM)</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div
                                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">RM</span>
                                                        </div>
                                                        <input type="number" name="packages[{{ $i }}][price]"
                                                            value="{{ $pkg['price'] }}" step="0.01"
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md px-3 py-2 border"
                                                            placeholder="0.00">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Duration
                                                        (Hours)</label>
                                                    <select name="packages[{{ $i }}][duration]"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 border bg-white">
                                                        <option value="">Select Duration</option>
                                                        @for ($h = 1; $h <= 6; $h++)
                                                            <option value="{{ $h }}"
                                                                {{ $pkg['duration'] == $h ? 'selected' : '' }}>
                                                                {{ $h }} Hour{{ $h > 1 ? 's' : '' }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-span-1 md:col-span-2">
                                                    <label
                                                        class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Frequency</label>
                                                    <select name="packages[{{ $i }}][frequency]"
                                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 border bg-white">
                                                        <option value="">Select Frequency</option>
                                                        <option value="Per Session"
                                                            {{ $pkg['frequency'] == 'Per Session' ? 'selected' : '' }}>Per
                                                            Session</option>
                                                        <option value="Weekly"
                                                            {{ $pkg['frequency'] == 'Weekly' ? 'selected' : '' }}>Weekly
                                                        </option>
                                                        <option value="Monthly"
                                                            {{ $pkg['frequency'] == 'Monthly' ? 'selected' : '' }}>Monthly
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Media</h3>
                            </div>
                            <div class="p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Image</label>
                                @php
                                    $currentImage = $service->image_path
                                        ? (Str::startsWith($service->image_path, 'services/')
                                            ? asset('storage/' . $service->image_path)
                                            : asset($service->image_path))
                                        : asset('images/default_service.jpg');
                                @endphp

                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative hover:bg-gray-50 transition-colors group cursor-pointer"
                                    onclick="document.getElementById('imageInput').click()">
                                    <div class="space-y-1 text-center">
                                        <div class="relative w-full h-48 mb-4">
                                            <img id="imagePreview" src="{{ $currentImage }}"
                                                class="w-full h-full object-cover rounded-lg shadow-sm">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all rounded-lg flex items-center justify-center">
                                                <span
                                                    class="text-white opacity-0 group-hover:opacity-100 font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full text-sm">Change</span>
                                            </div>
                                        </div>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <span
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload a file</span>
                                                <input id="imageInput" name="image" type="file" class="sr-only">
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Availability</h3>
                            </div>
                            <div class="p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unavailable Dates</label>
                                <div class="relative">
                                    <input type="text" id="unavailableDates" name="unavailable_dates"
                                        value="{{ implode(',', json_decode($service->unavailable_dates ?? '[]', true)) }}"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Select dates...">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Select dates when you are not available to provide
                                    this service.</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-custom-teal hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. SETUP QUILL EDITORS ---

            // Main Description Editor
            var quillMain = new Quill('#main-editor-container', {
                theme: 'snow',
                placeholder: 'Describe your service...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        ['clean']
                    ]
                }
            });

            // Package Editors
            var quillPkgs = [];
            for (let i = 0; i < 3; i++) {
                var q = new Quill('#pkg-editor-' + i, {
                    theme: 'snow',
                    placeholder: 'Package details...',
                    modules: {
                        // Simplified toolbar for smaller inputs
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{
                                'list': 'bullet'
                            }],
                            ['clean']
                        ]
                    }
                });
                quillPkgs.push(q);
            }

            // --- 2. EXISTING LOGIC ---

            // Image preview logic
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const originalImageSrc = "{{ $currentImage }}";

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => imagePreview.src = e.target.result;
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = originalImageSrc;
                }
            });

            // Flatpickr
            flatpickr("#unavailableDates", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                minDate: "today",
                conjunction: ", "
            });

            // Toggle extra packages
            const toggle = document.getElementById('togglePackages');
            const extraPackages = document.querySelectorAll('.extra-package');

            function updatePackageVisibility() {
                extraPackages.forEach(pkg => {
                    const inputs = pkg.querySelectorAll('input, select');
                    // Note: We don't disable Quill divs, visual hiding is enough for UX, 
                    // backend handles logic based on toggle state usually.
                    if (toggle.checked) {
                        pkg.style.display = 'block';
                        inputs.forEach(input => input.disabled = false);
                    } else {
                        pkg.style.display = 'none';
                        inputs.forEach(input => input.disabled = true);
                    }
                });
            }

            if (toggle) {
                toggle.addEventListener('change', updatePackageVisibility);
                updatePackageVisibility();
            }

            // --- 3. FORM SUBMISSION ---
            document.getElementById('serviceForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // >>> IMPORTANT: SYNC QUILL DATA TO HIDDEN INPUTS BEFORE SUBMIT <<<
                document.getElementById('hidden-description').value = quillMain.root.innerHTML;

                for (let i = 0; i < 3; i++) {
                    // Check if editor exists (it always should based on loop, but safety check)
                    if (document.getElementById('hidden-pkg-' + i)) {
                        document.getElementById('hidden-pkg-' + i).value = quillPkgs[i].root.innerHTML;
                    }
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerText;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Saving...';

                const formData = new FormData(this);

                fetch("{{ route('services.update', $service->id) }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (res.ok && data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'Service updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'Great!',
                                confirmButtonColor: '#4f46e5'
                            }).then(() => {
                                window.location.href = "{{ route('services.manage') }}";
                            });
                        } else {
                            let errorMessage = data.message || 'An error occurred while saving.';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('\n');
                            }
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        Swal.fire({
                            title: 'System Error',
                            text: 'Something went wrong.',
                            icon: 'error'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerText = originalBtnText;
                    });
            });
        });
    </script>
@endsection
