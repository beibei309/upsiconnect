<x-app-layout>

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
                        <button class="tab-btn disabled" data-target="description-section" disabled>
                            <span class="py-2 px-4 text-gray-500">Description</span>
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
                 <button type="button" data-target="pricing" class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
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
                                style="font-size: 20px; font-weight:600; color: #7C86FF">Basic
                                Package</label>

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
                                        <option value="daily">session</option>

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
                                            <option value="daily">session</option>
                                            <option value="daily">weekly</option>
                                            <option value="daily">monthly</option>

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
                                            <option value="daily">session</option>
                                            <option value="daily">weekly</option>
                                            <option value="daily">monthly</option>

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
               <button type="button" data-target="description-section" class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
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
<div id="description-section" class="tab-section mt-8 hidden">
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
                            <button type="button" data-target="publish" class="save-continue px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
    Save & Continue →
</button>

                        </div>
                    </div>


                    <!-- Publish Section -->
                    <div id="publish" class="mt-8 hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Publish Your Service</h2>
                            <p class="text-gray-600 mt-1">Review everything and publish your service.</p>
                        </div>
                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('services.manage') }}"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                Cancel
                            </a>
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
                </form>
            </div>
        </div>
    </div>
 

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const sections = document.querySelectorAll(".tab-section");
    const form = document.getElementById('createServiceForm');

    let serviceId = null; // store service ID after first save
    const completed = { overview: false, pricing: false, description: false };

    // Show a tab
    function showTab(id) {
        sections.forEach(sec => sec.classList.add("hidden"));
        document.getElementById(id).classList.remove("hidden");

        tabButtons.forEach(btn => btn.classList.remove("active"));
        const activeBtn = document.querySelector(`.tab-btn[data-target="${id}"]`);
        if(activeBtn) activeBtn.classList.add("active");
    }

    // Unlock next tab
    function unlockNextTab(nextId) {
        const btn = document.querySelector(`.tab-btn[data-target="${nextId}"]`);
        if(btn){
            btn.classList.remove("disabled");
            showTab(nextId);
        }
    }

    // Only check visible required fields
    function checkRequiredFields(section) {
        const requiredFields = section.querySelectorAll('[required]');
        for(let field of requiredFields){
            if(field.offsetParent === null) continue; // skip hidden
            if(!field.value.trim()){
                return false;
            }
        }
        return true;
    }

    // Save data via AJAX
    function saveData(sectionId) {
        const formData = new FormData(form);
        formData.append("current_section", sectionId);
        if(serviceId) formData.append("service_id", serviceId);

        fetch("{{ route('services.store') }}", {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                serviceId = data.service.id; // store service ID
                completed[sectionId] = true;

                // determine next tab
                let nextTabId = '';
                if(sectionId === 'overview') nextTabId = 'pricing';
                else if(sectionId === 'pricing') nextTabId = 'description-section';
                else if(sectionId === 'description-section') nextTabId = 'publish';

                if(nextTabId) unlockNextTab(nextTabId);
            } else {
                alert("Error saving data. Please try again.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error saving data. Please try again.");
        });
    }

    // Handle Save & Continue button click
    document.querySelectorAll('.save-continue').forEach(btn => {
        btn.addEventListener('click', function(){
            const targetId = this.dataset.target;
            const section = document.getElementById(targetId === 'pricing' ? 'overview' : targetId === 'description-section' ? 'pricing' : 'description-section');
            
            if(!checkRequiredFields(section)){
                alert('Please fill in all required fields before proceeding.');
                return;
            }

            saveData(section.id);
        });
    });

    // Tab button click
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if(btn.classList.contains('disabled')) return;
            showTab(btn.dataset.target);
        });
    });

    // Show first tab initially
    showTab('overview');

    // Toggle package section visibility
    document.getElementById('offer_packages')?.addEventListener('change', function() {
        const packageSection = document.getElementById('packageSection');
        if(this.checked) packageSection.classList.remove('hidden');
        else packageSection.classList.add('hidden');
    });

    // Template image selection
    const images = document.querySelectorAll('.template-image');
    images.forEach(img => {
        img.addEventListener('click', function() {
            images.forEach(i => {
                i.classList.remove('border-indigo-500');
                i.classList.add('border-gray-300');
            });
            this.classList.remove('border-gray-300');
            this.classList.add('border-indigo-500');

            const fileInput = document.getElementById('image');
            if(fileInput) fileInput.value = "";

            let input = document.getElementById('selected_template');
            if(!input){
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'template_image';
                input.id = 'selected_template';
                form.appendChild(input);
            }
            input.value = this.dataset.template;
        });
    });

});
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('nav a[href^="#"]'); // all tab links
            const sections = document.querySelectorAll('#overview, #pricing, #description-section, #publish');

            // Set the first tab to be visible on page load
            sections.forEach(sec => sec.classList.add('hidden')); // Hide all sections
            document.querySelector('#overview').classList.remove('hidden'); // Show the overview section initially

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault(); // prevent the default jump to anchor behavior

                    // Remove active classes from all tabs
                    tabs.forEach(t => t.classList.remove('text-indigo-600', 'border-indigo-600'));
                    tabs.forEach(t => t.classList.add('text-gray-500', 'hover:text-gray-700',
                        'hover:border-gray-300'));

                    // Add active classes to the clicked tab
                    this.classList.add('text-indigo-600', 'border-indigo-600');

                    // Hide all sections
                    sections.forEach(sec => sec.classList.add('hidden'));

                    // Get the target section based on the href attribute of the clicked tab
                    const target = document.querySelector(this.getAttribute('href'));

                    if (target) {
                        target.classList.remove('hidden'); // Show the clicked section
                    }
                });
            });
        });

        // DELETE SCRIPT - for handling delete requests on the form
        document.getElementById('deleteServiceForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Deleting...
            `;

            try {
                const response = await fetch('/services/delete', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage('Service deleted successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('services.manage') }}';
                    }, 1500);
                } else {
                    showMessage(data.error || 'Failed to delete service', 'error');
                }
            } catch (error) {
                showMessage('An error occurred. Please try again.', 'error');
            } finally {
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });

        function showMessage(message, type) {
            const messageContainer = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            messageDiv.className =
                `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-4 transform transition-all duration-300 translate-x-full`;
            messageDiv.textContent = message;

            messageContainer.appendChild(messageDiv);

            // Animate in
            setTimeout(() => {
                messageDiv.classList.remove('translate-x-full');
            }, 100);

            // Remove after 5 seconds
            setTimeout(() => {
                messageDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    messageContainer.removeChild(messageDiv);
                }, 300);
            }, 5000);
        }

    function loadPreview() {
    if (!serviceId) return;
    fetch(`/services/${serviceId}/details`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('preview-container').innerHTML = html;
        });
}

// Call this when publish tab is clicked
document.querySelector('.tab-btn[data-target="publish"]').addEventListener('click', loadPreview);

    </script>



    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>
</x-app-layout>
