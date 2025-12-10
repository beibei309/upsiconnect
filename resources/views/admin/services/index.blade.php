@extends('admin.layout')

@section('content')
<div class="px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Services</h1>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.services.index') }}" class="flex gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" placeholder="Search by title, description or student name..."
                       class="w-full pl-4 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ request('search') }}">
            </div>
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Search
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
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Approval</th>
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
                                            <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs">No Img</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $service->title }}</div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                            {{ Str::limit($service->description, 40) }}
                                        </div>
                                        @if($service->category)
                                            <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $service->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $service->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500">ID: {{ $service->user_id }}</div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="text-sm text-gray-700">
                                    @php
                                        $prices = array_filter([$service->basic_price, $service->standard_price, $service->premium_price]);
                                        $min = !empty($prices) ? min($prices) : 0;
                                        $max = !empty($prices) ? max($prices) : 0;
                                    @endphp
                                    @if($min > 0)
                                        {{ $min == $max ? 'RM '.number_format($min) : 'RM '.number_format($min).' - RM '.number_format($max) }}
                                    @else
                                        <span class="text-gray-400 italic">Not set</span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="py-4 px-6 text-center">
                                @if($service->approval_status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @elseif($service->approval_status === 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button type="button" 
                                            onclick="openServiceModal({{ json_encode($service) }}, '{{ $service->user->name ?? 'Unknown' }}', '{{ $service->category->name ?? 'N/A' }}')"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                        View
                                    </button>

                                    @if($service->approval_status === 'pending')
                                        <button onclick="confirmAction('{{ route('admin.services.approve', $service->id) }}', 'approve')" 
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                            Approve
                                        </button>
                                        <button onclick="confirmAction('{{ route('admin.services.reject', $service->id) }}', 'reject')" 
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                            Reject
                                        </button>
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

        @if($services->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>

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
                            <div class="border rounded-lg p-3 bg-purple-50 border-purple-100" id="pkg-premium">
                                <div class="text-xs font-bold text-purple-700 uppercase mb-1">Premium</div>
                                <div class="text-lg font-bold text-gray-900" id="modal-prem-price"></div>
                                <div class="text-xs text-gray-500 mt-1" id="modal-prem-desc"></div>
                                <div class="text-xs font-semibold text-gray-600 mt-2" id="modal-prem-duration"></div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                            <div>Provider: <span class="font-bold text-gray-700" id="modal-provider"></span></div>
                            <div>Created: <span id="modal-date"></span></div>
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

<form id="action-form" method="POST" style="display: none;">
    @csrf
    </form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- Modal Logic ---
    function openServiceModal(service, providerName, categoryName) {
        // Populate Fields
        document.getElementById('modal-title').textContent = service.title;
        document.getElementById('modal-category').textContent = categoryName;
        document.getElementById('modal-provider').textContent = providerName;
        document.getElementById('modal-description').textContent = service.description;
        
        // Status Badge
        const badge = document.getElementById('modal-status-badge');
        badge.textContent = service.approval_status.charAt(0).toUpperCase() + service.approval_status.slice(1);
        badge.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
            service.approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
            (service.approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
        }`;

        // Date
        const date = new Date(service.created_at);
        document.getElementById('modal-date').textContent = date.toLocaleDateString();

        // Image
        const img = document.getElementById('modal-image');
        if (service.image_path) {
            img.src = '/storage/' + service.image_path; // Adjust path if needed
            img.classList.remove('hidden');
        } else {
            img.src = 'https://via.placeholder.com/600x400?text=No+Image+Provided';
        }

        // --- Package Logic (Hide if null) ---
        
        // Basic
        if(service.basic_price) {
            document.getElementById('pkg-basic').classList.remove('hidden');
            document.getElementById('modal-basic-price').textContent = 'RM ' + service.basic_price;
            document.getElementById('modal-basic-desc').textContent = service.basic_description || 'No description';
            document.getElementById('modal-basic-duration').textContent = (service.basic_duration || 0) + ' hrs';
        } else {
            document.getElementById('pkg-basic').classList.add('hidden');
        }

        // Standard
        if(service.standard_price) {
            document.getElementById('pkg-standard').classList.remove('hidden');
            document.getElementById('modal-std-price').textContent = 'RM ' + service.standard_price;
            document.getElementById('modal-std-desc').textContent = service.standard_description || 'No description';
            document.getElementById('modal-std-duration').textContent = (service.standard_duration || 0) + ' hrs';
        } else {
            document.getElementById('pkg-standard').classList.add('hidden');
        }

        // Premium
        if(service.premium_price) {
            document.getElementById('pkg-premium').classList.remove('hidden');
            document.getElementById('modal-prem-price').textContent = 'RM ' + service.premium_price;
            document.getElementById('modal-prem-desc').textContent = service.premium_description || 'No description';
            document.getElementById('modal-prem-duration').textContent = (service.premium_duration || 0) + ' hrs';
        } else {
            document.getElementById('pkg-premium').classList.add('hidden');
        }

        // Show Modal
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
                // Create a form dynamically
                const form = document.createElement('form');
                form.action = url;
                form.method = 'POST'; // Keep this as POST
                
                // 1. Add CSRF Token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // 2. Add Method Spoofing for PATCH (This fixes the error)
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH'; 
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection