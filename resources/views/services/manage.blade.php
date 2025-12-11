@extends('layouts.helper')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <div class="mb-8 text-left mt-2">
                <h1 class="text-4xl font-bold text-gray-900">My Services</h1>
                <p class="text-gray-600 mt-2">Add, edit, or manage your services offered to the community</p>
            </div>

            <!-- Add Service Button -->
            <div class="mb-2 flex justify-end">
                <a href="{{ route('services.create') }}"
                    class="bg-custom-teal hover:bg-teal-800 text-white px-3 py-3 rounded-lg font-medium flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Add New Service</span>
                </a>
            </div>

            <!-- Tabs -->
            <div class="mb-6">
                <div class="flex space-x-4 border-b border-gray-200">
                    <button data-tab="all"
                        class="tab-btn py-2 px-4 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                        All
                    </button>
                    <button data-tab="pending"
                        class="tab-btn py-2 px-4 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                        Pending
                    </button>
                    <button data-tab="approved"
                        class="tab-btn py-2 px-4 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                        Approved
                    </button>
                    <button data-tab="rejected"
                        class="tab-btn py-2 px-4 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                        Rejected
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="tab-contents">
                @php
                    $statuses = ['all', 'pending', 'approved', 'rejected'];
                @endphp

                @foreach ($statuses as $status)
                    <div id="{{ $status }}-tab-content"
                        class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ $status !== 'all' ? 'hidden' : '' }}">
                        @php
                            $filtered = $status === 'all' ? $services : $services->where('approval_status', $status);
                        @endphp
                   @foreach ($filtered as $service)
    <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full"
         data-service-id="{{ $service->id }}">
        
        <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
            @if ($service->image_path)
                @php
                    $isStorageImage = Str::startsWith($service->image_path, 'services/');
                @endphp
                <img src="{{ $isStorageImage ? asset('storage/' . $service->image_path) : asset($service->image_path) }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     alt="{{ $service->title }}"
                     onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
            @else
                <img src="https://via.placeholder.com/400x300?text=No+Image"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     alt="default image">
            @endif
            
            <div class="absolute top-3 right-3">
                @php
                    $statusClasses = match (strtolower($service->approval_status)) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusClasses }} shadow-sm uppercase tracking-wide">
                    {{ $service->approval_status }}
                </span>
            </div>
        </div>

        <div class="p-5 flex-1 flex flex-col">
            
            @if ($service->category)
                <div class="mb-2">
                    <span class="inline-flex items-center text-xs font-bold tracking-wide uppercase"
                          style="color: {{ $service->category->color }};">
                        {{ $service->category->name }}
                    </span>
                </div>
            @endif

            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-teal-600 transition-colors">
                {{ $service->title }}
            </h3>

            <p class="text-gray-500 text-sm mb-5 line-clamp-2 flex-1 leading-relaxed">
                {{ $service->description }}
            </p>

            <div class="space-y-3 mb-6">
                @php $hasPrice = false; @endphp
                @foreach(['Basic' => 'basic', 'Standard' => 'standard', 'Premium' => 'premium'] as $label => $key)
                    @if($service->{$key.'_price'})
                        @php $hasPrice = true; @endphp
                        <div class="flex justify-between items-start text-sm p-2 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="flex-1 pr-2">
                                <span class="block font-medium text-gray-700">{{ $label }}</span>
                                @if($service->{$key.'_description'})
                                    <span class="block text-xs text-gray-400 mt-0.5 line-clamp-1" title="{{ $service->{$key.'_description'} }}">
                                        {{ $service->{$key.'_description'} }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="block font-bold text-gray-900">
                                    RM{{ number_format($service->{$key.'_price'}, 0) }}
                                </span>
                                <span class="block text-xs text-gray-400 font-normal">
                                    / {{ $service->{$key.'_frequency'} }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                @if(!$hasPrice)
                    <div class="p-3 bg-gray-50 rounded-lg text-center border border-gray-100">
                        <span class="text-xs text-gray-400 italic">No active pricing packages</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                <button onclick="editService({{ $service->id }})"
                        class="flex items-center text-gray-500 hover:text-teal-600 text-sm font-medium transition-colors px-2 py-1 rounded-md hover:bg-teal-50">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>

                <button onclick="deleteService({{ $service->id }})"
                        class="flex items-center text-gray-400 hover:text-red-600 text-sm font-medium transition-colors px-2 py-1 rounded-md hover:bg-red-50">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>
@endforeach
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-tab');

                    // Hide all
                    tabContents.forEach(tc => tc.classList.add('hidden'));
                    // Show target
                    document.getElementById(`${target}-tab-content`).classList.remove('hidden');

                    // Optional: active button styling
                    tabButtons.forEach(b => b.classList.remove('text-indigo-600', 'border-b-2',
                        'border-indigo-600'));
                    btn.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');

                });
            });

            // Activate default tab
            tabButtons[0].click();
        });

        // Handle service status toggle
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.service-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const serviceId = this.dataset.serviceId;
                    const isActive = this.checked;

                    fetch(`/student-services/${serviceId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                is_active: isActive
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.service) {
                                // Update status badge
                                const statusBadge = this.closest('.bg-white').querySelector(
                                    '.inline-flex.items-center.px-2.py-1');
                                const statusDot = statusBadge.querySelector('.w-1\\.5');

                                if (isActive) {
                                    statusBadge.className =
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                    statusDot.className =
                                        'w-1.5 h-1.5 rounded-full bg-green-400 mr-1';
                                    statusBadge.textContent = 'Active';
                                } else {
                                    statusBadge.className =
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                                    statusDot.className =
                                        'w-1.5 h-1.5 rounded-full bg-red-400 mr-1';
                                    statusBadge.textContent = 'Inactive';
                                }
                                statusBadge.prepend(statusDot);
                            } else {
                                // Revert toggle if update failed
                                this.checked = !isActive;
                                alert('Error updating service status. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Revert toggle if update failed
                            this.checked = !isActive;
                            alert('Error updating service status. Please try again.');
                        });
                });
            });
        });
        // Delete service
        function deleteService(serviceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('services.destroy', ':id') }}".replace(':id', serviceId);

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json())
                        .then(data => {
                            if (data.service) {
                                const card = document.querySelector(`[data-service-id='${serviceId}']`);
                                if (card) card.remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'The service has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    position: 'center'
                                });
                            } else {
                                Swal.fire('Error', data.error || 'Unable to delete service.', 'error');
                            }
                        }).catch(err => {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        });
                }
            });
        }

        function editService(serviceId) {
            window.location.href = `/services/${serviceId}/edit`;
        }
    </script>
@endsection
