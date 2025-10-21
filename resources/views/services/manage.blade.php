<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Manage Services</h1>
                        <p class="text-gray-600 mt-2">Add, edit, or manage your services offered to the community</p>
                    </div>
                    <a href="{{ route('services.create') }}" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add New Service</span>
                    </a>
                </div>
            </div>

            <!-- Services Grid -->
            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <!-- Service Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->title }}</h3>
                                    @if($service->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $service->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Status Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               class="sr-only peer service-toggle" 
                                               data-service-id="{{ $service->id }}"
                                               {{ $service->is_active ? 'checked' : '' }}>
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Service Description -->
                            @if($service->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $service->description }}</p>
                            @endif

                            <!-- Service Price -->
                            @if($service->suggested_price)
                                <div class="mb-4">
                                    <span class="text-lg font-semibold text-green-600">RM {{ number_format($service->suggested_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 ml-1">suggested price</span>
                                </div>
                            @endif

                            <!-- Service Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $service->is_active ? 'bg-green-400' : 'bg-red-400' }} mr-1"></div>
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        onclick="editService({{ $service->id }}, '{{ addslashes($service->title) }}', '{{ addslashes($service->description) }}', {{ $service->suggested_price ?? 'null' }}, {{ $service->category_id ?? 'null' }})"
                                        class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                        Edit
                                    </button>
                                    <button 
                                        onclick="deleteService({{ $service->id }})"
                                        class="text-red-600 hover:text-red-500 text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No services yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first service.</p>
                    <div class="mt-6">
                        <a href="{{ route('services.create') }}" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Add Your First Service
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function deleteService(serviceId) {
            if (confirm('Are you sure you want to delete this service?')) {
                fetch(`/student-services/${serviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.service) {
                        window.location.reload(); // Refresh to show updated services
                    } else {
                        alert('Error deleting service. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting service. Please try again.');
                });
            }
        }

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ is_active: isActive })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.service) {
                            // Update status badge
                            const statusBadge = this.closest('.bg-white').querySelector('.inline-flex.items-center.px-2.py-1');
                            const statusDot = statusBadge.querySelector('.w-1\\.5');
                            
                            if (isActive) {
                                statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                statusDot.className = 'w-1.5 h-1.5 rounded-full bg-green-400 mr-1';
                                statusBadge.textContent = 'Active';
                            } else {
                                statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                                statusDot.className = 'w-1.5 h-1.5 rounded-full bg-red-400 mr-1';
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
    </script>
</x-app-layout>