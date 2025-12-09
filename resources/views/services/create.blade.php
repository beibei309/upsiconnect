<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .tab-btn {
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn span {
            display: inline-block;
        }

        .tab-btn.active span {
            color: #4C51BF;
            font-weight: 600;
            border-bottom: 2px solid #4C51BF;
        }

        .tab-btn:hover span {
            color: #4C51BF;
            border-bottom: 2px solid #4C51BF;
        }

        .tab-btn.disabled span {
            color: #D1D5DB;
            cursor: not-allowed;
        }

        .tab-btn.disabled:hover span {
            color: #D1D5DB;
            border-bottom: none;
        }

        .toggle-switch {
            position: relative;
            cursor: pointer;
        }

        .toggle-switch input:checked+.toggle-switch>.toggle-circle {
            transform: translateX(100%);
            background-color: #4C51BF;
        }

        .toggle-circle {
            transition: transform 0.3s ease-in-out;
        }

        input[type="checkbox"]:checked+.toggle-switch>.toggle-circle {
            background-color: #4C51BF;
        }
    </style>


    <div class="py-8">
        <br><br><br>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header + Tabs -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Add New Service</h1>
                        <p class="text-gray-600 mt-2">Create a new service to offer to the community</p>
                    </div>
                    <a href="{{ route('services.manage') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Manage</span>
                    </a>
                </div>
                <div class="mt-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button class="tab-btn active" data-target="overview">
                            <span class="py-2 px-4 border-b-2 font-medium text-gray-900">Overview</span>
                        </button>
                        <button class="tab-btn disabled" data-target="pricing" disabled>
                            <span class="py-2 px-4 text-gray-500">Pricing</span>
                        </button>
                        <button class="tab-btn disabled" data-target="description" disabled>
                            <span class="py-2 px-4 text-gray-500">Description</span>
                        </button>
                        <button class="tab-btn disabled" data-target="availability" disabled>
                            <span class="py-2 px-4 text-gray-500">Availability</span>
                        </button>
                        <button class="tab-btn disabled" data-target="publish" disabled>
                            <span class="py-2 px-4 text-gray-500">Publish</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Service Creation Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form id="createServiceForm" action="{{ route('services.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Overview Section -->
                    <div id="overview" class="tab-section mt-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Start defining your service</h2>
                            <p class="text-gray-600 mt-1">Choose a clear, descriptive title and suitable category for
                                your service.</p>
                        </div>
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="e.g., Math Tutoring, Web Development, Graphic Design">
                        </div>
                        <br>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Help users find your service by selecting a relevant
                                category</p>
                        </div>

                        <!-- Service Image Upload -->
                        <div class="mt-8">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Image
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <p class="text-sm text-gray-500 mt-1">Upload an image representing your service (optional).
                            </p>

                            <!-- Template Images -->
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Or choose a template image:</p>

                                <div class="flex gap-4">
                                    <img src="/images/service_tutor.jpg" alt="Template 1"
                                        class="template-image w-24 h-24 object-cover rounded-lg border-2 cursor-pointer"
                                        data-template="/images/service_tutor.jpg">
                                    <img src="/images/priya.jpg" alt="Template 2"
                                        class="template-image w-24 h-24 object-cover rounded-lg border-2 cursor-pointer"
                                        data-template="/images/priya.jpg">
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    const images = document.querySelectorAll('.template-image');

                                    images.forEach(img => {
                                        img.addEventListener('click', function() {

                                            // Remove highlight
                                            images.forEach(i => {
                                                i.classList.remove('border-indigo-500');
                                                i.classList.add('border-gray-300');
                                            });

                                            // Add highlight to selected
                                            this.classList.remove('border-gray-300');
                                            this.classList.add('border-indigo-500');

                                            // Clear uploaded file if exist
                                            const fileInput = document.getElementById('image');
                                            if (fileInput) fileInput.value = "";

                                            // Create or update hidden input
                                            let input = document.getElementById('selected_template');
                                            if (!input) {
                                                input = document.createElement('input');
                                                input.type = 'hidden';
                                                input.name = 'template_image';
                                                input.id = 'selected_template';
                                                document.getElementById('createServiceForm').appendChild(input);
                                            }

                                            input.value = this.dataset.template;
                                        });
                                    });
                                });
                            </script>
                        </div>

                        <!-- Save & Continue Button -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit" name="save_step" value="overview" data-target="pricing"
                                class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>


                    {{-- Pricing and package --}}
                    <div id="pricing" class="tab-section mt-8 hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Scope & Pricing</h2>
                            <p class="text-gray-600 mt-1">Set your price for the service.</p>
                        </div>

                        <!-- Package 1 - Basic -->
                        <div class="mb-4">
                            <label for="package_name" class="block text-dark mb-2"
                                style="font-size: 20px; font-weight:600; color: #367588">Basic
                                Package</label>
                            <input type="hidden" name="packages[0][package_type]" value="basic">
                        </div>

                        <!-- Duration and Frequency -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-4">
                                <!-- Duration -->
                                <div class="flex-1">
                                    <label for="duration"
                                        class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                    <select id="duration0" name="packages[0][duration]" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="1">1 Hour</option>
                                        <option value="2">2 Hours</option>
                                        <option value="3">3 Hours</option>
                                        <option value="4">4 Hours</option>
                                        <option value="5">5 Hours</option>
                                    </select>
                                </div>

                                <!-- "per" Text -->
                                <span class="text-gray-700">per</span>

                                <!-- Frequency -->
                                <div class="flex-1">
                                    <label for="frequency0"
                                        class="block text-sm font-medium text-gray-700 mb-2">Frequency
                                    </label>
                                    <select id="frequency0" name="packages[0][frequency]" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="session">session</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Package Price -->
                        <div class="mb-4">
                            <label for="package_price0" class="block text-sm font-medium text-gray-700 mb-2">Package
                                Price (RM)</label>
                            <input type="number" id="package_price0" name="packages[0][price]" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="0.00">
                        </div>

                        <!-- Package Description -->
                        <div class="mb-4">
                            <label for="package_description0"
                                class="block text-sm font-medium text-gray-700 mb-2">Package Description</label>
                            <textarea id="package_description0" name="packages[0][description]" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="Describe the features and benefits of this package"></textarea>
                        </div>


                        <!-- Toggle for Packages -->
                        <div class="flex items-center space-x-4 mb-6">
                            <label for="offer_packages" class="text-gray-600">Offer Packages</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <!-- Toggle Switch -->
                                <input type="checkbox" id="offer_packages" class="hidden" />
                                <div class="toggle-switch bg-gray-300 w-12 h-6 rounded-full relative">
                                    <div
                                        class="toggle-circle bg-white w-6 h-6 rounded-full absolute left-0 top-0 transition-all">
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Pricing Section (Visible based on toggle) -->
                        <div id="packageSection" class="mt-8 hidden">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Add Pricing Packages</h3>
                                <p class="text-gray-600 mt-1">Define different packages for your service.</p>
                            </div>

                            <!-- Standard Package 2 -->
                            <div class="mb-4">
                                <label for="package_name" class="block text-dark mb-2"
                                    style="font-size: 20px; font-weight:600; color: #F0B13B">Standard
                                    Package</label>

                                <input type="hidden" name="packages[1][package_type]" value="standard">
                            </div>


                            <!-- Duration and Frequency -->
                            <div class="mb-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Duration -->
                                    <div class="flex-1">
                                        <label for="duration1"
                                            class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                        <select id="duration1" name="packages[1][duration]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <option value="">--Select an option--</option>
                                            <option value="1">1 Hour</option>
                                            <option value="2">2 Hours</option>
                                            <option value="3">3 Hours</option>
                                            <option value="4">4 Hours</option>
                                            <option value="5">5 Hours</option>
                                        </select>
                                    </div>

                                    <!-- "per" Text -->
                                    <span class="text-gray-700">per</span>

                                    <!-- Frequency -->
                                    <div class="flex-1">
                                        <label for="frequency1"
                                            class="block text-sm font-medium text-gray-700 mb-2">Frequency
                                        </label>
                                        <select id="frequency1" name="packages[1][frequency]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <option value="">--Select an option--</option>
                                            <option value="session">session</option>
                                            <option value="weekly">weekly</option>
                                            <option value="monthly">monthly</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Price -->
                            <div class="mb-4">
                                <label for="package_price1"
                                    class="block text-sm font-medium text-gray-700 mb-2">Package
                                    Price (RM)</label>
                                <input type="number" id="package_price1" name="packages[1][price]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="0.00">
                            </div>

                            <!-- Package Description -->
                            <div class="mb-4">
                                <label for="package_description1"
                                    class="block text-sm font-medium text-gray-700 mb-2">Package Description</label>
                                <textarea id="package_description1" name="packages[1][description]" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Describe the features and benefits of this package"></textarea>
                            </div>

                            <!-- Premium - Package 3 -->
                            <div class="mb-4">
                                <label for="package_name" class="block text-dark mb-2"
                                    style="font-size: 20px; font-weight:600; color: #E7180B">Premium
                                    Package</label>

                                <input type="hidden" name="packages[2][package_type]" value="premium">
                            </div>


                            <!-- Duration and Frequency -->
                            <div class="mb-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Duration -->
                                    <div class="flex-1">
                                        <label for="duration2"
                                            class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                        <select id="duration2" name="packages[2][duration]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <option value="">--Select an option--</option>
                                            <option value="1">1 Hour</option>
                                            <option value="2">2 Hours</option>
                                            <option value="3">3 Hours</option>
                                            <option value="4">4 Hours</option>
                                            <option value="5">5 Hours</option>
                                        </select>
                                    </div>

                                    <!-- "per" Text -->
                                    <span class="text-gray-700">per</span>

                                    <!-- Frequency -->
                                    <div class="flex-1">
                                        <label for="frequency2"
                                            class="block text-sm font-medium text-gray-700 mb-2">Frequency
                                        </label>
                                        <select id="frequency2" name="packages[2][frequency]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                            <option value="">--Select an option--</option>
                                            <option value="session">session</option>
                                            <option value="weekly">weekly</option>
                                            <option value="monthly">monthly</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Price -->
                            <div class="mb-4">
                                <label for="package_price2"
                                    class="block text-sm font-medium text-gray-700 mb-2">Package
                                    Price (RM)</label>
                                <input type="number" id="package_price2" name="packages[2][price]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="0.00">
                            </div>

                            <!-- Package Description -->
                            <div class="mb-4">
                                <label for="package_description2"
                                    class="block text-sm font-medium text-gray-700 mb-2">Package Description</label>
                                <textarea id="package_description2" name="packages[2][description]" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="Describe the features and benefits of this package"></textarea>
                            </div>
                        </div>



                        <!-- Save & Continue Button -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit" name="save_step" value="pricing" data-target="description"
                                class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>


                    </div>
                    <script>
                        document.getElementById('offer_packages').addEventListener('change', function() {
                            const packageSection = document.getElementById('packageSection');
                            if (this.checked) {
                                packageSection.classList.remove('hidden');
                            } else {
                                packageSection.classList.add('hidden');
                            }
                        });
                    </script>

                    <!-- Description Section -->
                    <div id="description" class="tab-section mt-8 hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Service Description</h2>
                            <p class="text-gray-600 mt-1">
                                Describe your service in detail. What do you offer? What makes you qualified?
                                What can clients expect?
                            </p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>

                            <textarea id="description" name="description" rows="8" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="Describe your service in detail. What do you offer? What makes you qualified? What can clients expect?"></textarea>

                            <p class="text-sm text-gray-500 mt-1">
                                Provide a detailed description to help potential clients understand your service.
                            </p>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" name="save_step" value="description" data-target="availability"
                                class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>

                    <!-- Availability Section -->
                    <div id="availability" class="tab-section mt-8 hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Availability</h2>
                            <p class="text-gray-600 mt-1">
                                Pilih tarikh yang anda TIDAK available untuk menerima service.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Unavailable Dates
                            </label>

                            <input id="unavailable_dates" name="unavailable_dates"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg 
            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="Choose dates" />

                            <p class="text-sm text-gray-500 mt-1">
                                Tarikh yang dipilih akan ditanda sebagai warna merah pada calendar.
                            </p>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" name="save_step" value="availability" data-target="publish"
                                class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {

                            // Use $service only if it exists, otherwise default to empty array
                            let existingUnavailable = @json(isset($service) && $service->unavailable_dates ? json_decode($service->unavailable_dates) : []);

                            flatpickr("#unavailable_dates", {
                                mode: "multiple",
                                dateFormat: "Y-m-d",
                                defaultDate: existingUnavailable,
                                onDayCreate: function(dObj, dStr, fp, dayElem) {
                                    let date = dayElem.dateObj.toISOString().split("T")[0];
                                    if (existingUnavailable.includes(date)) {
                                        dayElem.classList.add("unavailable-dates");
                                    }
                                },
                                onChange: function(selectedDates, dateStr, instance) {
                                    let formattedDates = selectedDates.map(d => d.toISOString().split("T")[0]);
                                    instance.input.value = formattedDates.join(",");

                                    instance.calendarContainer.querySelectorAll(".flatpickr-day").forEach(dayElem => {
                                        let date = dayElem.dateObj.toISOString().split("T")[0];
                                        if (formattedDates.includes(date)) {
                                            dayElem.classList.add("unavailable-dates");
                                        } else {
                                            dayElem.classList.remove("unavailable-dates");
                                        }
                                    });
                                }
                            });
                        });
                    </script>

                   <!-- Publish Section -->
<div id="publish" class="mt-8 hidden">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Publish Your Service</h2>
        <p class="text-gray-600 mt-1">Review everything before publishing your service.</p>
    </div>

    <!-- Live Preview Container -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h3>

        <!-- Two-column layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Service Title -->
                <div>
                    <h4 class="text-sm text-gray-600">Service Title:</h4>
                    <p id="previewTitle" class="text-lg font-semibold text-gray-800">Loading...</p>
                </div>

                <!-- Category -->
                <div>
                    <h4 class="text-sm text-gray-600">Category:</h4>
                    <p id="previewCategory" class="text-md text-gray-800">Loading...</p>
                </div>

                <!-- Availability -->
                <div>
                    <h4 class="text-sm text-gray-600">Unavailable dates:</h4>
                    <p id="previewAvailability" class="text-gray-800">Select a date to check availability</p>
                </div>

                <!-- Description -->
                <div>
                    <h4 class="text-sm text-gray-600">Description:</h4>
                    <p id="previewDescription" class="text-gray-800">Loading...</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Image -->
                <div>
                    <h4 class="text-sm text-gray-600">Service Image:</h4>
                    <div id="previewImage"
                        class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">Image Preview</span>
                    </div>
                </div>

                <!-- Packages -->
                <div>
                    <h4 class="text-sm text-gray-600 mb-2">Packages:</h4>
                    <div id="previewPackages" class="grid grid-cols-1 gap-4">
                        <p class="text-gray-500">No packages added</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
        <a href="{{ route('services.manage') }}"
            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancel</a>
        <button type="submit"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Create Service</span>
        </button>
    </div>
</div>

<!-- Script for Live Preview -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inputs
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category_id');
    const imageInput = document.getElementById('image');
    const descriptionInput = document.getElementById('description');
    const dateInput = document.getElementById('unavailable_dates');

    // Preview Elements
    const previewTitle = document.getElementById('previewTitle');
    const previewCategory = document.getElementById('previewCategory');
    const previewImage = document.getElementById('previewImage');
    const previewPackages = document.getElementById('previewPackages');
    const previewAvailability = document.getElementById('previewAvailability');
    const previewDescription = document.getElementById('previewDescription');

    // Update functions
    titleInput.addEventListener('input', () => {
        previewTitle.textContent = titleInput.value || 'Service Title';
    });

    categorySelect.addEventListener('change', () => {
        const selected = categorySelect.options[categorySelect.selectedIndex];
        previewCategory.textContent = selected ? selected.text : 'Select a category';
    });

    imageInput.addEventListener('change', (event) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.style.backgroundImage = `url(${e.target.result})`;
            previewImage.style.backgroundSize = 'cover';
            previewImage.style.backgroundPosition = 'center';
            previewImage.textContent = '';
        };
        if (imageInput.files[0]) reader.readAsDataURL(imageInput.files[0]);
    });

    descriptionInput.addEventListener('input', () => {
        previewDescription.textContent = descriptionInput.value || 'Service description';
    });

    dateInput.addEventListener('change', () => {
        previewAvailability.textContent = dateInput.value || 'Select a date to check availability';
    });

    // Dynamic Packages Preview
    function updatePackages() {
        previewPackages.innerHTML = '';

        const packageElements = document.querySelectorAll('[name^="packages"]');
        let packagesAdded = false;

        // Loop through all package inputs (assumes your package inputs follow this naming)
        const totalPackages = 3; // Basic, Standard, Premium
        for (let i = 0; i < totalPackages; i++) {
            const type = document.querySelector(`[name="packages[${i}][package_type]"]`)?.value;
            const price = document.querySelector(`[name="packages[${i}][price]"]`)?.value;
            const duration = document.querySelector(`[name="packages[${i}][duration]"]`)?.value;
            const frequency = document.querySelector(`[name="packages[${i}][frequency]"]`)?.value;
            const description = document.querySelector(`[name="packages[${i}][description]"]`)?.value;

            if (type && (price || duration || frequency || description)) {
                packagesAdded = true;
                const card = document.createElement('div');
                card.classList.add('bg-white', 'border', 'border-gray-200', 'rounded-lg', 'p-4', 'shadow-sm');

                const titleEl = document.createElement('h5');
                titleEl.classList.add('text-md', 'font-semibold', 'text-gray-800');
                titleEl.textContent = type.charAt(0).toUpperCase() + type.slice(1) + ' Package';

                const priceEl = document.createElement('p');
                priceEl.classList.add('text-gray-700');
                priceEl.textContent = `Price: RM ${price || '0.00'}`;

                const durationEl = document.createElement('p');
                durationEl.classList.add('text-gray-700');
                durationEl.textContent = `Duration: ${duration || '-'} ${frequency || ''}`;

                const descEl = document.createElement('p');
                descEl.classList.add('text-gray-600', 'mt-2');
                descEl.textContent = description || 'No description';

                card.appendChild(titleEl);
                card.appendChild(priceEl);
                card.appendChild(durationEl);
                card.appendChild(descEl);

                previewPackages.appendChild(card);
            }
        }

        if (!packagesAdded) {
            previewPackages.innerHTML = '<p class="text-gray-500">No packages added</p>';
        }
    }

    // Listen for changes in package inputs
    document.querySelectorAll('[name^="packages"]').forEach(input => {
        input.addEventListener('input', updatePackages);
    });

    // Initialize preview
    previewTitle.textContent = titleInput.value || 'Service Title';
    previewCategory.textContent = categorySelect.options[categorySelect.selectedIndex]?.text || 'Select a category';
    previewDescription.textContent = descriptionInput.value || 'Service description';
    previewAvailability.textContent = dateInput.value || 'Select a date to check availability';
    updatePackages();
});
</script>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById('createServiceForm');
            const tabButtons = document.querySelectorAll(".tab-btn");
            const sections = document.querySelectorAll(".tab-section");
            let serviceId = "{{ $service->id ?? '' }}";

            function showTab(id) {
                sections.forEach(sec => sec.classList.add("hidden"));
                document.getElementById(id).classList.remove("hidden");
                tabButtons.forEach(btn => btn.classList.remove("active"));
                const activeBtn = document.querySelector(`.tab-btn[data-target="${id}"]`);
                if (activeBtn) activeBtn.classList.add("active");
            }

            function unlockTab(id) {
                const btn = document.querySelector(`.tab-btn[data-target="${id}"]`);
                if (btn) {
                    btn.classList.remove("disabled");
                    btn.disabled = false;
                }
            }

            function checkRequiredFields(section) {
                const requiredFields = section.querySelectorAll('[required]');
                for (let field of requiredFields) {
                    if (field.offsetParent === null) continue; // hidden
                    if (!field.value.trim()) return false;
                }
                return true;
            }

            async function saveSection(sectionId) {
                const formData = new FormData(form);
                formData.append("current_section", sectionId);
                if (serviceId) formData.append("service_id", serviceId);

                // include all fields from pricing section
                if (sectionId === 'pricing') {
                    document.querySelectorAll('#pricing select, #pricing input, #pricing textarea').forEach(
                        el => {
                            if (el.name) formData.append(el.name, el.value);
                        });
                }

                try {
                    const res = await fetch("{{ route('services.store') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        serviceId = data.service.id;
                        let nextTab = '';
                        if (sectionId === 'overview') nextTab = 'pricing';
                        else if (sectionId === 'pricing') nextTab = 'description';
                        else if (sectionId === 'description') nextTab = 'availability';
                        else if (sectionId === 'availability') nextTab = 'publish';

                        if (nextTab) {
                            unlockTab(nextTab);
                            showTab(nextTab);
                        }
                    } else {
                        alert(data.error || "Error saving data.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("Error saving data. Please try again.");
                }
            }

            // Save & Continue buttons
            document.querySelectorAll('.save-continue').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    let currentSection = '';

                    if (btn.dataset.target === 'pricing') currentSection = 'overview';
                    else if (btn.dataset.target === 'description') currentSection = 'pricing';
                    else if (btn.dataset.target === 'availability') currentSection = 'description';
                    else if (btn.dataset.target === 'publish') currentSection = 'availability';

                    if (!checkRequiredFields(document.getElementById(currentSection))) {
                        alert('Please fill all required fields before proceeding.');
                        return;
                    }
                    saveSection(currentSection);
                });
            });

            // Tab buttons
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (btn.classList.contains('disabled')) return;
                    showTab(btn.dataset.target);
                });
            });

            showTab('overview');
        });
    </script>


    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>
</x-app-layout>
