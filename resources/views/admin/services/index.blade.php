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
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -30px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.7rem;
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

                {{-- Submit --}}
                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Search
                </button>
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-64">Service
                                Details</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Seller</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pricing</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Availability</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Avg Rating</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Reviews</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Warning</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status</th>
                            <th class="py-4 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50 transition-colors">

                                <td class="py-4 px-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
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
                                            <div class="text-sm font-bold text-gray-900 line-clamp-1"
                                                title="{{ $service->title }}">{{ $service->title }}</div>
                                            @if ($service->category)
                                                <span
                                                    class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">
                                                    {{ $service->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->user->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-gray-500">ID: {{ $service->user_id }}</div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="text-sm text-gray-700 whitespace-nowrap">
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
                                            {{ $min == $max ? 'RM ' . number_format($min) : 'RM ' . number_format($min) . ' - ' . number_format($max) }}
                                        @else
                                            <span class="text-gray-400 italic">Not set</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if ($service->is_active)
                                        <span
                                            class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Available</span>
                                    @else
                                        <span
                                            class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Unavailable</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span
                                            class="text-sm font-bold text-gray-800">{{ number_format($service->average_rating ?? 0, 1) }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if (($service->reviews_count ?? 0) > 0)
                                        <button onclick="alert('View Reviews Modal Placeholder')"
                                            class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                            View all {{ $service->reviews_count ?? 0 }} reviews
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">No reviews</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @php
                                        $warnings = $service->warning_count ?? 0;
                                        // Get the reason safely
                                        $latestReason = $service->warning_reason ?? 'No details';
                                    @endphp

                                    <div class="tooltip">
                                        <span
                                            class="font-mono text-sm font-bold cursor-default {{ $warnings >= 2 ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $warnings }}/3
                                        </span>
                                        @if ($warnings > 0)
                                            <span class="tooltip-text"
                                                style="width: 150px; margin-left: -75px; bottom: 100%;">
                                                {{ Str::limit($latestReason, 50) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if ($service->approval_status === 'approved')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                    @elseif($service->approval_status === 'rejected')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    @elseif($service->approval_status === 'blocked')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-800 text-white">Blocked</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <div class="tooltip">
                                            <button
                                                onclick="openServiceModal({{ json_encode($service) }}, '{{ $service->user->name ?? 'Unknown' }}', '{{ $service->category->name ?? 'N/A' }}')"
                                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <span class="tooltip-text">View</span>
                                        </div>

                                        @if ($service->approval_status === 'pending' || $service->approval_status === 'rejected')
                                            <div class="tooltip">
                                                <button
                                                    onclick="confirmAction('{{ route('admin.services.approve', $service->id) }}', 'approve')"
                                                    class="p-1.5 text-green-600 hover:bg-green-50 rounded-md transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <span class="tooltip-text">Approve</span>
                                            </div>
                                        @endif

                                        @if ($service->approval_status === 'pending' || $service->approval_status === 'approved')
                                            <div class="tooltip">
                                                <button
                                                    onclick="confirmAction('{{ route('admin.services.reject', $service->id) }}', 'reject')"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <span class="tooltip-text">Reject</span>
                                            </div>
                                        @endif

                                        <div class="tooltip">
                                            <button onclick="sendWarning({{ $service->id }}, '{{ $service->title }}')"
                                                class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-md transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </button>
                                            <span class="tooltip-text">Warning</span>
                                        </div>


                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
<<<<<<< HEAD
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
=======
                                <td colspan="9" class="px-6 py-10 text-center text-gray-500">
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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

<<<<<<< HEAD
    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeServiceModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

=======
    <form id="action-form" method="POST" style="display: none;">@csrf</form>

    <div id="serviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeServiceModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-1 space-y-6">
                            <div
                                class="w-full aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img id="modal-image" src="" class="w-full h-full object-cover" alt="Service Image">
=======
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1 space-y-6">
                            <div
                                class="w-full aspect-video bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img id="modal-image" src="" class="w-full h-full object-cover"
                                    alt="Service Image">
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

                        <div class="lg:col-span-2 flex flex-col h-full">

=======
                        <div class="lg:col-span-2 flex flex-col h-full">
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

=======
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

=======
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

=======
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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
<<<<<<< HEAD

                        </div>
                    </div>
                </div>

=======
                        </div>
                    </div>
                </div>
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
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

<<<<<<< HEAD
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
=======
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal Logic (Same as before)
        function openServiceModal(service, providerName, categoryName) {
            document.getElementById('modal-title').textContent = service.title;
            document.getElementById('modal-category').textContent = categoryName;
            document.getElementById('modal-provider').textContent = providerName;
            document.getElementById('modal-provider-initial').textContent = providerName.charAt(0).toUpperCase();
            document.getElementById('modal-description').innerHTML = service.description;

            const badge = document.getElementById('modal-status-badge');
            badge.textContent = service.approval_status.charAt(0).toUpperCase() + service.approval_status.slice(1);
            badge.className = `px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                service.approval_status === 'approved' ? 'bg-green-100 text-green-800' : 
                (service.approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 
                (service.approval_status === 'blocked' ? 'bg-gray-800 text-white' : 'bg-yellow-100 text-yellow-800'))
            }`;

            const date = new Date(service.created_at);
            document.getElementById('modal-date').textContent = date.toLocaleDateString();

>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
            const img = document.getElementById('modal-image');
            if (service.image_path) {
                img.src = '/storage/' + service.image_path;
                img.classList.remove('hidden');
            } else {
                img.src = 'https://via.placeholder.com/600x400?text=No+Image';
<<<<<<< HEAD
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
=======
            }

            // Packages logic (Basic, Standard, Premium) - Keeping your existing logic
            if (service.basic_price) {
                document.getElementById('pkg-basic').classList.remove('hidden');
                document.getElementById('modal-basic-price').textContent = 'RM ' + service.basic_price;
                document.getElementById('modal-basic-desc').innerHTML = service.basic_description || 'No description';
                document.getElementById('modal-basic-duration').textContent = (service.basic_duration || 0) + ' Hours';
                document.getElementById('modal-basic-frequency').textContent = service.basic_frequency || 'Session';
            } else {
                document.getElementById('pkg-basic').classList.add('hidden');
            }

            if (service.standard_price) {
                document.getElementById('pkg-standard').classList.remove('hidden');
                document.getElementById('modal-std-price').textContent = 'RM ' + service.standard_price;
                document.getElementById('modal-std-desc').innerHTML = service.standard_description || 'No description';
                document.getElementById('modal-std-duration').textContent = (service.standard_duration || 0) + ' Hours';
                document.getElementById('modal-std-frequency').textContent = service.standard_frequency || 'Session';
            } else {
                document.getElementById('pkg-standard').classList.add('hidden');
            }

            if (service.premium_price) {
                document.getElementById('pkg-premium').classList.remove('hidden');
                document.getElementById('modal-prem-price').textContent = 'RM ' + service.premium_price;
                document.getElementById('modal-prem-desc').innerHTML = service.premium_description || 'No description';
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

        // Updated Action Logic
        function confirmAction(url, action) {
            let title = 'Are you sure?';
            let text = '';
            let confirmBtnColor = '#3085d6';

            switch (action) {
                case 'approve':
                    title = 'Approve Service?';
                    text = 'This service will become visible to the public.';
                    confirmBtnColor = '#10B981';
                    break;
                case 'reject':
                    title = 'Reject Service?';
                    text = 'This service will be rejected and hidden.';
                    confirmBtnColor = '#EF4444';
                    break;
                case 'block':
                    title = 'Block Service?';
                    text = 'This service will be permanently blocked.';
                    confirmBtnColor = '#1F2937'; // Dark gray
                    break;
            }

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
                    submitForm(url, 'PATCH');
                }
            });
        }

        // Specific Warning Logic (with Input)

        function sendWarning(serviceId, serviceTitle) {
            Swal.fire({
                title: 'Send Warning',
                text: `Issue a warning for "${serviceTitle}"? The student will be notified via email.`,
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Reason for warning',
                inputPlaceholder: 'Type your reason here (e.g., Inappropriate image, Price too low)...',
                showCancelButton: true,
                confirmButtonColor: '#F59E0B',
                confirmButtonText: 'Send Warning & Email',
                showLoaderOnConfirm: true, // Show loading spinner while processing
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('You need to write a reason!')
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Define the URL based on your route
                    let url = `/admin/services/${serviceId}/warning`;

                    // Create a hidden form to submit the POST request with the reason
                    const form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';
                    form.style.display = 'none'; // Hide the form

                    // CSRF Token (Required for Laravel POST requests)
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // The Reason Input
                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'reason'; // This matches $request->input('reason') in Controller
                    reasonInput.value = result.value;
                    form.appendChild(reasonInput);

                    // Append to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function submitForm(url, method) {
            const form = document.createElement('form');
            form.action = url;
            form.method = 'POST';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            if (method !== 'POST') {
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = method;
                form.appendChild(methodField);
            }

            document.body.appendChild(form);
            form.submit();
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
        }
    </script>
@endsection
