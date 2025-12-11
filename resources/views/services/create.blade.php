@extends('layouts.helper')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-8 mt-2">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Service</h1>
                    <p class="text-gray-600 mt-2">Create a new service to offer to the community</p>
                </div>
                <a href="{{ route('services.manage') }}"
                    class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center space-x-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Manage</span>
                </a>
            </div>

            <div class="mb-8 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="tab-btn active group" data-target="overview" onclick="switchTab('overview')">
                        <span class="py-4 px-1 border-b-2 border-indigo-500 font-medium text-indigo-600 text-sm">Overview</span>
                    </button>
                    <button class="tab-btn disabled group" data-target="pricing" disabled>
                        <span class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-400 text-sm group-hover:text-gray-500">Pricing</span>
                    </button>
                    <button class="tab-btn disabled group" data-target="description" disabled>
                        <span class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-400 text-sm group-hover:text-gray-500">Description</span>
                    </button>
                    <button class="tab-btn disabled group" data-target="availability" disabled>
                        <span class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-400 text-sm group-hover:text-gray-500">Availability</span>
                    </button>
                    <button class="tab-btn disabled group" data-target="publish" disabled>
                        <span class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-400 text-sm group-hover:text-gray-500">Publish</span>
                    </button>
                </nav>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form id="createServiceForm" onsubmit="return false;" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="service_id" id="service_id" value="{{ $service->id ?? '' }}">

                    <div id="overview" class="tab-section">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Start defining your service</h2>
                            <p class="text-gray-600 mt-1">Choose a clear, descriptive title.</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Service Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., Math Tutoring">
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Image</label>
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                
                                <p class="text-sm font-medium text-gray-700 mt-4 mb-2">Or choose a template:</p>
                                <div class="flex gap-4">
                                    <img src="/images/service_tutor.jpg" class="template-image w-24 h-24 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-indigo-500 transition" data-val="/images/service_tutor.jpg">
                                    <img src="/images/priya.jpg" class="template-image w-24 h-24 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-indigo-500 transition" data-val="/images/priya.jpg">
                                </div>
                                <input type="hidden" name="template_image" id="template_image">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="processSection('overview', 'pricing')" 
                                class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                Save & Continue →
                            </button>
                        </div>
                    </div>

                    <div id="pricing" class="tab-section hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Scope & Pricing</h2>
                        </div>

                        <div class="border rounded-xl p-5 mb-6 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Basic Package</h3>
                            <input type="hidden" name="packages[0][package_type]" value="basic">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Price (RM)</label>
                                    <input type="number" name="packages[0][price]" required class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Duration</label>
                                    <select name="packages[0][duration]" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        @for($i=1; $i<=6; $i++) <option value="{{$i}}">{{$i}} Hour{{$i>1?'s':''}}</option> @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Frequency</label>
                                <select name="packages[0][frequency]" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Per Session">Per Session</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Description</label>
                                <textarea name="packages[0][description]" rows="2" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center mb-6">
                            <input type="checkbox" id="offer_packages" name="offer_packages" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="offer_packages" class="ml-2 block text-sm text-gray-900">Offer Standard & Premium Packages?</label>
                        </div>

                        <div id="extraPackages" class="hidden space-y-6">
                            <div class="border rounded-xl p-5 bg-blue-50 border-blue-100">
                                <h3 class="text-lg font-bold text-blue-800 mb-4">Standard Package</h3>
                                <input type="hidden" name="packages[1][package_type]" value="standard">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div><label class="text-xs font-bold text-blue-600">Price (RM)</label><input type="number" name="packages[1][price]" class="w-full border-blue-200 rounded-md"></div>
                                    <div>
                                        <label class="text-xs font-bold text-blue-600">Duration</label>
                                        <select name="packages[1][duration]" class="w-full border-blue-200 rounded-md">
                                            @for($i=1; $i<=6; $i++) <option value="{{$i}}">{{$i}} Hour{{$i>1?'s':''}}</option> @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="text-xs font-bold text-blue-600">Frequency</label>
                                    <select name="packages[1][frequency]" class="w-full border-blue-200 rounded-md">
                                        <option value="Per Session">Per Session</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                                <textarea name="packages[1][description]" rows="2" class="w-full border-blue-200 rounded-md" placeholder="Description"></textarea>
                            </div>

                            <div class="border rounded-xl p-5 bg-purple-50 border-purple-100">
                                <h3 class="text-lg font-bold text-purple-800 mb-4">Premium Package</h3>
                                <input type="hidden" name="packages[2][package_type]" value="premium">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div><label class="text-xs font-bold text-purple-600">Price (RM)</label><input type="number" name="packages[2][price]" class="w-full border-purple-200 rounded-md"></div>
                                    <div>
                                        <label class="text-xs font-bold text-purple-600">Duration</label>
                                        <select name="packages[2][duration]" class="w-full border-purple-200 rounded-md">
                                            @for($i=1; $i<=6; $i++) <option value="{{$i}}">{{$i}} Hour{{$i>1?'s':''}}</option> @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="text-xs font-bold text-purple-600">Frequency</label>
                                    <select name="packages[2][frequency]" class="w-full border-purple-200 rounded-md">
                                        <option value="Per Session">Per Session</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                    </select>
                                </div>
                                <textarea name="packages[2][description]" rows="2" class="w-full border-purple-200 rounded-md" placeholder="Description"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="processSection('pricing', 'description')" 
                                class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>

                    <div id="description" class="tab-section hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Service Description</h2>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Description <span class="text-red-500">*</span></label>
                            <textarea id="desc_input" name="description" rows="8" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Describe what makes you qualified..."></textarea>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="processSection('description', 'availability')" 
                                class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>

                    <div id="availability" class="tab-section hidden">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Availability</h2>
                            <p class="text-gray-600">Select dates you are NOT available.</p>
                        </div>
                        <div class="mb-6">
                            <input id="unavailable_dates" name="unavailable_dates" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Select Dates">
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="processSection('availability', 'publish')" 
                                class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                Save & Continue →
                            </button>
                        </div>
                    </div>

                    <div id="publish" class="tab-section hidden">
                        <div class="text-center py-10">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Ready to Publish!</h2>
                            <p class="text-gray-600 mb-8">Your service details have been saved. Click below to finish.</p>
                            <a href="{{ route('services.manage') }}" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg">
                                Publish Service
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. Template Image Logic
        document.querySelectorAll('.template-image').forEach(img => {
            img.addEventListener('click', function() {
                document.querySelectorAll('.template-image').forEach(i => i.classList.remove('ring-4', 'ring-indigo-300'));
                this.classList.add('ring-4', 'ring-indigo-300');
                document.getElementById('template_image').value = this.dataset.val;
                document.getElementById('image').value = ""; // Clear upload if template selected
            });
        });

        // 2. Extra Packages Toggle
        document.getElementById('offer_packages').addEventListener('change', function() {
            document.getElementById('extraPackages').classList.toggle('hidden', !this.checked);
        });

        // 3. Flatpickr
        flatpickr("#unavailable_dates", { mode: "multiple", dateFormat: "Y-m-d", minDate: "today" });

        // 4. Tab Switching Logic (Visual Only)
        function switchTab(targetId) {
            // Hide all sections
            document.querySelectorAll('.tab-section').forEach(el => el.classList.add('hidden'));
            // Show target
            document.getElementById(targetId).classList.remove('hidden');
            
            // Update Headers
            document.querySelectorAll('.tab-btn span').forEach(el => {
                el.classList.remove('border-indigo-500', 'text-indigo-600');
                el.classList.add('border-transparent', 'text-gray-400');
            });
            
            // Highlight Current Button
            const btn = document.querySelector(`button[data-target="${targetId}"] span`);
            if(btn) {
                btn.classList.remove('border-transparent', 'text-gray-400');
                btn.classList.add('border-indigo-500', 'text-indigo-600');
                // Enable button parent
                btn.parentElement.disabled = false;
                btn.parentElement.classList.remove('disabled');
            }
        }

        // 5. MAIN SAVE FUNCTION (AJAX)
        async function processSection(currentSection, nextSection) {
            const form = document.getElementById('createServiceForm');
            const formData = new FormData(form);
            
            // Append critical data manually to ensure it's sent
            formData.append('current_section', currentSection);
            
            // Check for service_id
            const serviceId = document.getElementById('service_id').value;
            if(serviceId) {
                formData.append('service_id', serviceId);
            }

            // Client-side validation (Simple)
            if(currentSection === 'overview') {
                if(!formData.get('title') || !formData.get('category_id')) {
                    Swal.fire('Missing Info', 'Please fill in Title and Category', 'warning');
                    return;
                }
            }

            // Show Loading
            Swal.fire({ title: 'Saving...', didOpen: () => Swal.showLoading() });

            try {
                const response = await fetch("{{ route('services.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // FORCE JSON RESPONSE
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.close();
                    
                    // Update Service ID for next steps
                    if (data.service && data.service.id) {
                        document.getElementById('service_id').value = data.service.id;
                    }

                    // Move to next tab
                    switchTab(nextSection);
                    
                    // Optional: Small toast
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                    });
                    Toast.fire({ icon: 'success', title: 'Saved successfully' });

                } else {
                    Swal.fire('Error', data.error || 'Something went wrong', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('System Error', 'Check console for details', 'error');
            }
        }
    </script>
@endsection