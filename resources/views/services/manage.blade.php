@extends('layouts.helper')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My Services</h1>
                    <p class="text-gray-500 mt-1">Manage your service portfolio</p>
                </div>
                
                <a href="{{ route('services.create') }}"
                    class="mt-4 md:mt-0 inline-flex items-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Service
                </a>
            </div>

            <div class="mb-8 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    @foreach(['all' => 'All Services', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
                        <button data-tab="{{ $key }}"
                            class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $key === 'all' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div id="tab-contents">
                @php $statuses = ['all', 'pending', 'approved', 'rejected']; @endphp

                @foreach ($statuses as $status)
                    <div id="{{ $status }}-tab-content"
                        class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 {{ $status !== 'all' ? 'hidden' : '' }}">
                        @php
                            $filtered = $status === 'all' ? $services : $services->where('approval_status', $status);
                        @endphp
                        
                        @foreach ($filtered as $service)
                            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full overflow-hidden relative"
                                 data-service-id="{{ $service->id }}">
                                
                                <div class="hidden" id="data-desc-{{ $service->id }}">{!! $service->description !!}</div>
                                <div class="hidden" id="data-pkg-basic-desc-{{ $service->id }}">{!! $service->basic_description !!}</div>
                                <div class="hidden" id="data-pkg-standard-desc-{{ $service->id }}">{!! $service->standard_description !!}</div>
                                <div class="hidden" id="data-pkg-premium-desc-{{ $service->id }}">{!! $service->premium_description !!}</div>

                                <div class="relative h-48 overflow-hidden bg-gray-100">
                                    @php $isStorageImage = Str::startsWith($service->image_path, 'services/'); @endphp
                                    <img src="{{ $isStorageImage ? asset('storage/' . $service->image_path) : asset($service->image_path) }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         alt="{{ $service->title }}"
                                         onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                    
                                    <div class="absolute top-3 right-3">
                                        @php
                                            $badgeColor = match(strtolower($service->approval_status)) {
                                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'approved' => 'bg-green-100 text-green-800 border-green-200',
                                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider border {{ $badgeColor }}">
                                            {{ $service->approval_status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    @if ($service->category)
                                        <span class="text-xs font-bold uppercase tracking-wide mb-2 block" style="color: {{ $service->category->color }}">
                                            {{ $service->category->name }}
                                        </span>
                                    @endif

                                    <h3 class="text-lg font-bold text-gray-900 mb-4 line-clamp-1" title="{{ $service->title }}">
                                        {{ $service->title }}
                                    </h3>

                                    <div class="space-y-2 mb-6 bg-gray-50 rounded-xl p-3 border border-gray-100">
                                        @php $hasPrice = false; @endphp
                                        @foreach(['Basic' => 'basic', 'Standard' => 'standard', 'Premium' => 'premium'] as $label => $key)
                                            @if($service->{$key.'_price'})
                                                @php $hasPrice = true; @endphp
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-500 font-medium">{{ $label }}</span>
                                                    <div class="text-right">
                                                        <span class="font-bold text-gray-900">RM{{ number_format($service->{$key.'_price'}) }}</span>
                                                        <span class="text-xs text-gray-400">/{{ $service->{$key.'_frequency'} }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(!$hasPrice)
                                            <div class="text-center text-xs text-gray-400 italic">No pricing available</div>
                                        @endif
                                    </div>

                                    <div class="mt-auto flex items-center gap-3">
                                        <button onclick="openServiceModal({{ json_encode($service) }})"
                                            class="flex-1 bg-white border border-gray-200 hover:border-teal-500 hover:text-teal-600 text-gray-700 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                                            View Details
                                        </button>

                                        <button onclick="editService({{ $service->id }})" class="p-2 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <button onclick="deleteService({{ $service->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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

    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeServiceModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl w-full">
                
                <div class="relative h-48 bg-gray-100">
                    <img id="modalImage" src="" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <button onclick="closeServiceModal()" class="absolute top-4 right-4 bg-white/20 hover:bg-white/40 text-white rounded-full p-1 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="absolute bottom-4 left-6 text-white">
                        <span id="modalCategory" class="text-xs font-bold uppercase tracking-wider mb-1 block opacity-90"></span>
                        <h3 id="modalTitle" class="text-2xl font-bold leading-tight"></h3>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">About this Service</h4>
                        <div id="modalDescription" class="rich-text text-gray-600 text-sm leading-relaxed max-h-60 overflow-y-auto pr-2 modern-scrollbar"></div>
                    </div>

                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Packages & Pricing</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="modalPackagesContainer">
                        </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="button" onclick="closeServiceModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rich-text ul { list-style-type: disc; padding-left: 1.25rem; margin-bottom: 0.5rem; }
        .rich-text ol { list-style-type: decimal; padding-left: 1.25rem; margin-bottom: 0.5rem; }
        .rich-text p { margin-bottom: 0.5rem; }
        .modern-scrollbar::-webkit-scrollbar { width: 4px; }
        .modern-scrollbar::-webkit-scrollbar-thumb { background-color: #D1D5DB; border-radius: 4px; }
    </style>

    <script>
        // 1. Tab Switching Logic
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-tab');
                
                // Hide all
                tabContents.forEach(tc => tc.classList.add('hidden'));
                document.getElementById(`${target}-tab-content`).classList.remove('hidden');

                // Styling
                tabButtons.forEach(b => {
                    b.classList.remove('border-teal-500', 'text-teal-600');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                btn.classList.remove('border-transparent', 'text-gray-500');
                btn.classList.add('border-teal-500', 'text-teal-600');
            });
        });

        // 2. Modal Logic
        function openServiceModal(service) {
            const modal = document.getElementById('serviceModal');
            
            // Populate Basic Info
            document.getElementById('modalTitle').textContent = service.title;
            const imgPath = service.image_path.startsWith('services/') ? `/storage/${service.image_path}` : service.image_path;
            document.getElementById('modalImage').src = imgPath;
            
            if(service.category) {
                const catEl = document.getElementById('modalCategory');
                catEl.textContent = service.category.name;
                catEl.style.color = service.category.color; // Or keep it white text
            }

            // Populate Rich Text Description (Get from hidden div to preserve HTML)
            const descContent = document.getElementById(`data-desc-${service.id}`).innerHTML;
            document.getElementById('modalDescription').innerHTML = descContent;

            // Populate Packages
            const pkgContainer = document.getElementById('modalPackagesContainer');
            pkgContainer.innerHTML = ''; // Clear previous

            ['basic', 'standard', 'premium'].forEach(level => {
                const price = service[`${level}_price`];
                if(price) {
                    const capitalLevel = level.charAt(0).toUpperCase() + level.slice(1);
                    // Get hidden rich text description for package
                    const pkgDesc = document.getElementById(`data-pkg-${level}-desc-${service.id}`).innerHTML;
                    
                    // Colors based on level
                    let bgClass = level === 'basic' ? 'bg-gray-50 border-gray-200' : (level === 'standard' ? 'bg-blue-50 border-blue-200' : 'bg-purple-50 border-purple-200');
                    let titleColor = level === 'basic' ? 'text-gray-800' : (level === 'standard' ? 'text-blue-800' : 'text-purple-800');

                    const html = `
                        <div class="border rounded-xl p-4 ${bgClass}">
                            <div class="flex justify-between items-center mb-2">
                                <h5 class="font-bold ${titleColor}">${capitalLevel}</h5>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">RM${price}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold mb-3">${service[`${level}_frequency`]}</div>
                            <div class="text-xs text-gray-600 rich-text border-t border-gray-200/50 pt-2">
                                ${pkgDesc || '<span class="italic text-gray-400">No description</span>'}
                            </div>
                        </div>
                    `;
                    pkgContainer.innerHTML += html;
                }
            });

            if(pkgContainer.innerHTML === '') {
                pkgContainer.innerHTML = '<div class="col-span-3 text-center text-gray-400 italic">No packages configured for this service.</div>';
            }

            // Show Modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }

        // 3. Navigation Functions
        function editService(id) { window.location.href = `/services/${id}/edit`; }

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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.service) {
                            document.querySelector(`[data-service-id='${serviceId}']`).remove();
                            Swal.fire('Deleted!', 'Service deleted.', 'success');
                        } else {
                            Swal.fire('Error', 'Unable to delete.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection