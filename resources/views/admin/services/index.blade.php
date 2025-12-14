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
    </style>

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
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service
                                Details</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Provider</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pricing</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Approval</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                            @if ($service->image_path)
                                                <img src="{{ asset('storage/' . $service->image_path) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div
                                                    class="h-full w-full flex items-center justify-center text-gray-400 text-xs">
                                                    No Img</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $service->title }}</div>
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                                {{ Str::limit(strip_tags($service->description), 40) }}
                                            </div>
                                            @if ($service->category)
                                                <span
                                                    class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $service->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->user->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-gray-500">ID: {{ $service->user_id }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-700">
                                        @php
                                            $prices = array_filter([
                                                $service->basic_price,
                                                $service->standard_price,
                                                $service->premium_price,
                                            ]);
                                            $min = !empty($prices) ? min($prices) : 0;
                                            $max = !empty($prices) ? max($prices) : 0;
                                        @endphp
                                        @if ($min > 0)
                                            {{ $min == $max ? 'RM ' . number_format($min) : 'RM ' . number_format($min) . ' - RM ' . number_format($max) }}
                                        @else
                                            <span class="text-gray-400 italic">Not set</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($service->approval_status === 'approved')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                    @elseif($service->approval_status === 'rejected')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <button type="button"
                                            onclick="openServiceModal({{ json_encode($service) }}, '{{ $service->user->name ?? 'Unknown' }}', '{{ $service->category->name ?? 'N/A' }}')"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                            View
                                        </button>
                                        @if ($service->approval_status === 'pending')
                                            <button
                                                onclick="confirmAction('{{ route('admin.services.approve', $service->id) }}', 'approve')"
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md text-xs font-medium transition">Approve</button>
                                            <button
                                                onclick="confirmAction('{{ route('admin.services.reject', $service->id) }}', 'reject')"
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">Reject</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <p>No services found.</p>
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

    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeServiceModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title"></h3>
                        <p class="text-sm text-gray-500 mt-1" id="modal-category"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="modal-status-badge"
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"></span>
                        <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none"
                            onclick="closeServiceModal()">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-1 space-y-6">
                            <div
                                class="w-full aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img id="modal-image" src="" class="w-full h-full object-cover" alt="Service Image">
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Provider Details
                                </h4>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        <span id="modal-provider-initial">U</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900" id="modal-provider"></div>
                                        <div class="text-xs text-gray-500">Created: <span id="modal-date"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 flex flex-col h-full">

                            <div class="mb-8">
                                <h4
                                    class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    About this Service
                                </h4>
                                <div class="text-sm text-gray-700 leading-relaxed rich-text bg-white"
                                    id="modal-description"></div>
                            </div>

                            <div class="mt-auto">
                                <h4
                                    class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    Pricing Packages
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="border rounded-xl p-4 bg-white hover:border-blue-300 transition-colors shadow-sm"
                                        id="pkg-basic">
                                        <div class="text-xs font-bold text-blue-600 uppercase mb-2 tracking-wider">Basic
                                        </div>
                                        <div class="text-2xl font-bold text-gray-900" id="modal-basic-price"></div>
                                        <div class="text-xs font-medium text-gray-500 mt-1 mb-3 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="modal-basic-duration"></span><span
                                                id="modal-basic-frequency"></span>
                                        </div>
                                        <div class="text-xs text-gray-600 rich-text border-t pt-3 mt-1"
                                            id="modal-basic-desc"></div>
                                    </div>

                                    <div class="border rounded-xl p-4 bg-white hover:border-yellow-300 transition-colors shadow-sm"
                                        id="pkg-standard">
                                        <div class="text-xs font-bold text-yellow-600 uppercase mb-2 tracking-wider">
                                            Standard</div>
                                        <div class="text-2xl font-bold text-gray-900" id="modal-std-price"></div>
                                        <div class="text-xs font-medium text-gray-500 mt-1 mb-3 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="modal-std-duration"></span><span id="modal-std-frequency"></span>
                                        </div>
                                        <div class="text-xs text-gray-600 rich-text border-t pt-3 mt-1"
                                            id="modal-std-desc"></div>
                                    </div>

                                    <div class="border rounded-xl p-4 bg-white hover:border-purple-300 transition-colors shadow-sm"
                                        id="pkg-premium">
                                        <div class="text-xs font-bold text-purple-600 uppercase mb-2 tracking-wider">
                                            Premium</div>
                                        <div class="text-2xl font-bold text-gray-900" id="modal-prem-price"></div>
                                        <div class="text-xs font-medium text-gray-500 mt-1 mb-3 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="modal-prem-duration"></span><span id="modal-prem-frequency"></span>
                                        </div>
                                        <div class="text-xs text-gray-600 rich-text border-t pt-3 mt-1"
                                            id="modal-prem-desc"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse border-t border-gray-200">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeServiceModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="action-form" method="POST" style="display: none;">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openServiceModal(service, providerName, categoryName) {
            document.getElementById('modal-title').textContent = service.title;
            document.getElementById('modal-category').textContent = categoryName;
            document.getElementById('modal-provider').textContent = providerName;
            document.getElementById('modal-provider-initial').textContent = providerName.charAt(0).toUpperCase();

            // Render HTML Description
            document.getElementById('modal-description').innerHTML = service.description;

            // Status Badge
            const badge = document.getElementById('modal-status-badge');
            badge.textContent = service.approval_status.charAt(0).toUpperCase() + service.approval_status.slice(1);
            badge.className = `px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
            service.approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
            (service.approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
        }`;

            // Date
            const date = new Date(service.created_at);
            document.getElementById('modal-date').textContent = date.toLocaleDateString();

            // Image
            const img = document.getElementById('modal-image');
            if (service.image_path) {
                img.src = '/storage/' + service.image_path;
                img.classList.remove('hidden');
            } else {
                img.src = 'https://via.placeholder.com/600x400?text=No+Image';
            }

            // --- Package Logic ---

            // Basic
            if (service.basic_price) {
                document.getElementById('pkg-basic').classList.remove('hidden');
                document.getElementById('modal-basic-price').textContent = 'RM ' + service.basic_price;
                document.getElementById('modal-basic-desc').innerHTML = service.basic_description || 'No description';

                // Populate Duration and Frequency spans
                document.getElementById('modal-basic-duration').textContent = (service.basic_duration || 0) + ' Hours';
                document.getElementById('modal-basic-frequency').textContent = service.basic_frequency || 'Session';
            } else {
                document.getElementById('pkg-basic').classList.add('hidden');
            }

            // Standard
            if (service.standard_price) {
                document.getElementById('pkg-standard').classList.remove('hidden');
                document.getElementById('modal-std-price').textContent = 'RM ' + service.standard_price;
                document.getElementById('modal-std-desc').innerHTML = service.standard_description || 'No description';

                // Populate Duration and Frequency spans
                document.getElementById('modal-std-duration').textContent = (service.standard_duration || 0) + ' Hours';
                document.getElementById('modal-std-frequency').textContent = service.standard_frequency || 'Session';
            } else {
                document.getElementById('pkg-standard').classList.add('hidden');
            }

            // Premium
            if (service.premium_price) {
                document.getElementById('pkg-premium').classList.remove('hidden');
                document.getElementById('modal-prem-price').textContent = 'RM ' + service.premium_price;
                document.getElementById('modal-prem-desc').innerHTML = service.premium_description || 'No description';

                // Populate Duration and Frequency spans
                document.getElementById('modal-prem-duration').textContent = (service.premium_duration || 0) + ' Hours';
                document.getElementById('modal-prem-frequency').textContent = service.premium_frequency || 'Session';
            } else {
                document.getElementById('pkg-premium').classList.add('hidden');
            }

            document.getElementById('serviceModal').classList.remove('hidden');
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.add('hidden');
        }

        function confirmAction(url, action) {
            const title = action === 'approve' ? 'Approve Service?' : 'Reject Service?';
            const text = action === 'approve' ? "This service will become visible to the public." :
                "This service will be rejected.";
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
                    methodField.value = 'PATCH';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
