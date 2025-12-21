@extends('admin.layout')

@section('content')
    <style>
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

        .rich-text p {
            margin-bottom: 0.5rem;
        }

        .rich-text strong {
            font-weight: 600;
        }

        /* Tooltip container */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 60px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 50;
            bottom: 125%;
            left: 50%;
            margin-left: -30px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.7rem;
            pointer-events: none;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>

    <div class="px-6 py-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Manage Services</h1>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex flex-wrap gap-4">

                {{-- Search --}}
                <div class="flex-1 min-w-[250px]">
                    <input type="text" name="search" placeholder="Search by title, description or student name..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ request('search') }}">
                </div>

                {{-- Category Filter --}}
                <div>
                    <select name="category" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Student Seller Filter --}}
                <div>
                    <select name="student" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Students</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ request('student') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service Details</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Provider</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pricing</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Warnings</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                            @if($service->image_path)
                                                <img src="{{ asset('storage/' . $service->image_path) }}" class="h-full w-full object-cover">
                                            @else
                                                <span class="text-gray-400 italic">Not set</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $service->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit(strip_tags($service->description), 50) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ $service->user->name ?? 'Unknown' }}
                                </td>

                                <td class="py-4 px-6 text-sm text-gray-600">
                                    @if($service->basic_price)
                                        RM {{ $service->basic_price }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    @if($service->approval_status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                    @elseif($service->approval_status === 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    @elseif($service->approval_status === 'suspended')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-white">Suspended</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    
                                    <div class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $service->is_active ? 'Online' : 'Offline' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-center">
                                    @php
                                        $warnings = $service->warning_count ?? 0;
                                        $latestReason = $service->warning_reason ?? 'No details';
                                    @endphp

                                    <div class="tooltip">
                                        <span class="font-mono text-sm font-bold cursor-default {{ $warnings >= 2 ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $warnings }}/3
                                        </span>
                                        @if ($warnings > 0)
                                            <span class="tooltip-text" style="width: 150px; margin-left: -75px; bottom: 100%;">
                                                {{ Str::limit($latestReason, 50) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <button type="button" 
                                                onclick="openServiceModal({{ json_encode($service) }}, '{{ $service->user->name ?? 'Unknown' }}', '{{ $service->category->name ?? 'N/A' }}')"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                            View
                                        </button>

                                        @if($service->approval_status !== 'rejected')
                                        <button type="button" 
                                                onclick="openWarningModal('{{ route('admin.services.warn', $service->id) }}')"
                                                class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                            Warning
                                        </button>
                                        @endif

                                   @if($service->approval_status === 'pending')
    <div class="flex items-center gap-2">
        {{-- APPROVE FORM --}}
        <form action="{{ route('admin.services.approve', $service->id) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to approve this service?');">
            @csrf
            @method('PATCH') {{-- This fixes the error --}}
            
            <button type="submit" 
                    class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md text-xs font-medium transition">
                Approve
            </button>
        </form>

        {{-- REJECT FORM --}}
        <form action="{{ route('admin.services.reject', $service->id) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to reject this service?');">
            @csrf
            @method('PATCH') {{-- This fixes the error --}}
            
            <button type="submit" 
                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                Reject
            </button>
        </form>
    </div>
@endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p>No services found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($services->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">{{ $services->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Service Modal --}}
    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeServiceModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                                    <p class="text-sm text-gray-500" id="modal-category"></p>
                                </div>
                                <span id="modal-status-badge" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"></span>
                            </div>
                            <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden mb-4 border border-gray-200">
                                <img id="modal-image" src="" class="w-full h-full object-cover" alt="Service Image">
                            </div>
                            <div class="mb-6">
                                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Description</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100" id="modal-description"></p>
                            </div>
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Packages</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                <div class="border rounded-lg p-3 bg-blue-50 border-blue-100" id="pkg-basic">
                                    <div class="text-xs font-bold text-blue-700 uppercase mb-1">Basic</div>
                                    <div class="text-lg font-bold text-gray-900" id="modal-basic-price"></div>
                                    <div class="text-xs text-gray-500 mt-1" id="modal-basic-desc"></div>
                                    <div class="text-xs font-semibold text-gray-600 mt-2" id="modal-basic-duration"></div>
                                </div>
                                <div class="border rounded-lg p-3 bg-yellow-50 border-yellow-100" id="pkg-standard">
                                    <div class="text-xs font-bold text-yellow-700 uppercase mb-1">Standard</div>
                                    <div class="text-lg font-bold text-gray-900" id="modal-std-price"></div>
                                    <div class="text-xs text-gray-500 mt-1" id="modal-std-desc"></div>
                                    <div class="text-xs font-semibold text-gray-600 mt-2" id="modal-std-duration"></div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Provider Details</h4>
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                            <span id="modal-provider-initial">U</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900" id="modal-provider"></div>
                                            <div class="text-xs text-gray-500">Created: <span id="modal-date"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeServiceModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Warning Modal --}}
    <div id="warningModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeWarningModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="warningForm" method="POST" action="">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Issue Warning
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Please state the reason for this warning. This will be emailed to the student.
                                    </p>
                                    <textarea name="reason" rows="4" class="w-full shadow-sm focus:ring-orange-500 focus:border-orange-500 mt-1 block sm:text-sm border border-gray-300 rounded-md p-2" placeholder="Example: Inappropriate service description..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Send Warning
                        </button>
                        <button type="button" onclick="closeWarningModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- Javascript Baru untuk Warning Modal ---
        function openWarningModal(url) {
            document.getElementById('warningForm').action = url;
            document.getElementById('warningModal').classList.remove('hidden');
        }

        function closeWarningModal() {
            document.getElementById('warningModal').classList.add('hidden');
        }

        // --- Javascript Asal (Service Detail Modal) ---
        function openServiceModal(service, providerName, categoryName) {
            document.getElementById('modal-title').textContent = service.title;
            document.getElementById('modal-category').textContent = categoryName;
            document.getElementById('modal-provider').textContent = providerName;
            document.getElementById('modal-description').textContent = service.description;
            
            const badge = document.getElementById('modal-status-badge');
            
            // --- LOGIC BARU (HANDLE SUSPENDED) ---
            let badgeClass = 'bg-yellow-100 text-yellow-800'; // Default Pending
            if (service.approval_status === 'approved') {
                badgeClass = 'bg-green-100 text-green-800';
            } else if (service.approval_status === 'rejected') {
                badgeClass = 'bg-red-100 text-red-800';
            } else if (service.approval_status === 'suspended') {
                badgeClass = 'bg-gray-800 text-white'; // Style untuk Suspended
            }

            badge.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeClass}`;
            badge.textContent = service.approval_status.charAt(0).toUpperCase() + service.approval_status.slice(1);

            const date = new Date(service.created_at);
            document.getElementById('modal-date').textContent = date.toLocaleDateString();

            const img = document.getElementById('modal-image');
            if (service.image_path) {
                img.src = '/storage/' + service.image_path; 
                img.classList.remove('hidden');
            } else {
                img.src = 'https://via.placeholder.com/600x400?text=No+Image+Provided';
            }

            // Logic Harga Basic
            if(service.basic_price) {
                document.getElementById('pkg-basic').classList.remove('hidden');
                document.getElementById('modal-basic-price').textContent = 'RM ' + service.basic_price;
                document.getElementById('modal-basic-desc').textContent = service.basic_description || 'No description';
                document.getElementById('modal-basic-duration').textContent = (service.basic_duration || 0) + ' hrs';
            } else {
                document.getElementById('pkg-basic').classList.add('hidden');
            }

            // Logic Harga Standard
            if(service.standard_price) {
                document.getElementById('pkg-standard').classList.remove('hidden');
                document.getElementById('modal-std-price').textContent = 'RM ' + service.standard_price;
                document.getElementById('modal-std-desc').textContent = service.standard_description || 'No description';
                document.getElementById('modal-std-duration').textContent = (service.standard_duration || 0) + ' hrs';
            } else {
                document.getElementById('pkg-standard').classList.add('hidden');
            }

            // Logic Harga Premium (Note: Your modal HTML was missing pkg-premium elements in the logic but had them in loops previously. Added checking logic assuming elements exist or hidden)
            // Note: In your HTML structure above, you have "pkg-standard" but I didn't see "pkg-premium" ID in the modal HTML part you provided. 
            // I'll leave checking to avoid JS errors if element missing.
            const premPkg = document.getElementById('pkg-premium');
            if(premPkg) {
                if(service.premium_price) {
                    premPkg.classList.remove('hidden');
                    document.getElementById('modal-prem-price').textContent = 'RM ' + service.premium_price;
                    document.getElementById('modal-prem-desc').textContent = service.premium_description || 'No description';
                    document.getElementById('modal-prem-duration').textContent = (service.premium_duration || 0) + ' hrs';
                } else {
                    premPkg.classList.add('hidden');
                }
            }

            document.getElementById('serviceModal').classList.remove('hidden');
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.add('hidden');
        }

        // --- Action Logic (Approve/Reject) ---
       function confirmAction(url, action) {
            const title = action === 'approve' ? 'Approve Service?' : 'Reject Service?';
            const text = action === 'approve' 
                ? "This service will become visible to the public." 
                : "This service will be rejected.";
            const confirmBtnColor = action === 'approve' ? '#10B981' : '#EF4444';

            // FIXED: Defined the method variable here
            const method = 'POST'; 

            Swal.fire({
                title: title,
                text: text,
                icon: action === 'approve' ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, ' + action + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST'; 
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = method;
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection