@extends('layouts.helper')

@section('content')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <br><br>
            @php
                // Define the default tab based on user role
                $defaultTab = auth()->user()->role === 'helper' ? 'received' : 'sent';

                // Define the default status tab (pending by default)
                $defaultStatusTab = 'pending';
            @endphp

            <!-- Received Requests Tab -->
            @if (auth()->user()->role === 'helper')
                <div id="received-content" class="sr-tab-content {{ $defaultTab === 'received' ? '' : 'hidden' }}"
                    style="{{ $defaultTab === 'received' ? 'display:block' : 'display:none' }}">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">My Service Requested ({{ $receivedRequests->count() }}total)
                            </h3>
                            <!-- Search Bar -->
                            <div class="mb-4">
                                <input type="text" id="request-search" placeholder="Search requests..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-teal focus:border-custom-teal">
                            </div>


                            <!-- Tabs for Pending, In Progress, Completed -->
                            <div class="mb-6">
                                <div class="flex space-x-4 border-b border-gray-200">
                                    <button onclick="showStatusTab('pending')" id="pending-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        Pending Request
                                    </button>
                                    <button onclick="showStatusTab('in-progress')" id="in-progress-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        In Progress
                                    </button>
                                    <button onclick="showStatusTab('completed')" id="completed-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        Completed
                                    </button>
                                </div>
                            </div>

                            <!-- Pending Requests Content -->
                            <div id="pending-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'pending' ? '' : 'hidden' }}">
                                @if ($receivedRequests->where('status', 'pending')->isEmpty())
                                    <div class="text-center py-8">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Requests</h3>
                                        <p class="mt-1 text-sm text-gray-500">You have no pending service requests.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('services.index') }}"
                                                class="inline-flex items-center px-4 py-2 shadow-sm text-sm font-medium rounded-md
                                                    bg-custom-teal text-white border border-transparent
                                                    hover:bg-white hover:text-custom-teal hover:border-custom-teal transition">
                                                Browse Services
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($receivedRequests->where('status', 'pending') as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 sr-request-item">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                            From: {{ $request->requester->name }}
                                                        </p>

                                                          @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                                @if ($request->studentService && !is_null($request->studentService->suggested_price))
                                                                    <span class="text-xs text-gray-500">(Suggested: RM
                                                                        {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                                @endif
                                                            </p>
                                                        @endif

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>

                                                    <div class="flex space-x-2">
                                                        @if ($request->isPending())
                                                            <!-- Accept Button -->
                                                            <button onclick="acceptRequest({{ $request->id }})"
                                                                class="px-4 py-1.5 text-sm font-semibold text-green-700 hover:text-white border border-green-600 hover:bg-green-600 transition-all duration-200 rounded-lg shadow-sm">
                                                                Accept
                                                            </button>

                                                            <!-- Hidden POST Form -->
                                                            <form id="accept-form-{{ $request->id }}"
                                                                action="{{ route('service-requests.accept', $request->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                            </form>

                                                            <!-- Reject Button -->
                                                            <button onclick="rejectRequest({{ $request->id }})"
                                                                class="px-4 py-1.5 text-sm font-semibold text-red-600 hover:text-white border border-red-500 hover:bg-red-600 transition-all duration-200 rounded-lg shadow-sm">
                                                                Reject
                                                            </button>

                                                            <!-- Hidden POST Form -->
                                                            <form id="reject-form-{{ $request->id }}"
                                                                action="{{ route('service-requests.reject', $request->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- In Progress Content -->
                            <div id="in-progress-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'in_progress' ? '' : 'hidden' }}">
                                @if ($receivedRequests->whereIn('status', ['accepted', 'in_progress'])->isEmpty())
                                    <div class="text-center py-8">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Ongoing Requests</h3>
                                        <p class="mt-1 text-sm text-gray-500">You have no ongoing service requests.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($receivedRequests->whereIn('status', ['accepted', 'in_progress']) as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 sr-request-item">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                            From: {{ $request->requester->name }}
                                                        </p>

                                                          @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                            </p>
                                                        @endif

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>

                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        @if ($request->isAccepted())
                                                            <button onclick="markInProgress({{ $request->id }})"
                                                                class="px-4 py-1.5 text-sm font-semibold text-blue-700 hover:text-white border border-blue-600 hover:bg-blue-600 transition-all duration-200 rounded-lg shadow-sm">
                                                                Start Work
                                                            </button>

                                                            <!-- Hidden form -->
                                                            <form id="progress-form-{{ $request->id }}"
                                                                action="{{ route('service-requests.mark-in-progress', $request->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                            </form>

                                                            <!-- WhatsApp Contact Button -->
                                                            <div class="mt-3">
                                                                <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center px-4 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-all duration-200">
                                                                    Contact via WhatsApp
                                                                </a>
                                                            </div>
                                                        @endif

                                                        @if ($request->isInProgress())
                                                            <!-- Mark Complete Button -->
                                                            <button onclick="markCompleted({{ $request->id }})"
                                                                class="px-4 py-1.5 text-sm font-semibold text-indigo-700 hover:text-white border border-indigo-600 hover:bg-indigo-600 transition-all duration-200 rounded-lg shadow-sm">
                                                                Mark Complete
                                                            </button>

                                                            <!-- Hidden form -->
                                                            <form id="completed-form-{{ $request->id }}"
                                                                action="{{ route('service-requests.mark-completed', $request->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                            </form>


                                                            <!-- WhatsApp Contact Button -->
                                                            <div class="mt-3">
                                                                <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center px-4 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-all duration-200">
                                                                    Contact via WhatsApp
                                                                </a>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>


                            <!-- Completed Content -->
                            <div id="completed-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'completed' ? '' : 'hidden' }}">
                                @if ($receivedRequests->whereIn('status', ['completed', 'cancelled', 'rejected'])->isEmpty())
                                    <div class="text-center py-8">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Completed Requests</h3>
                                        <p class="mt-1 text-sm text-gray-500">You have no completed service requests.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($receivedRequests->whereIn('status', ['completed', 'cancelled', 'rejected']) as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 sr-request-item">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                            From: {{ $request->requester->name }}
                                                        </p>

                                                          @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                                @if ($request->studentService && !is_null($request->studentService->suggested_price))
                                                                    <span class="text-xs text-gray-500">(Suggested: RM
                                                                        {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                                @endif
                                                            </p>
                                                        @endif

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>

                                                    <div class="flex space-x-2">

                                                        @if (
                                                            $request->isCompleted() &&
                                                                !$request->reviews()->where('reviewer_id', auth()->id())->exists())
                                                            <button
                                                                onclick="openReviewModal({{ $request->id }}, '{{ $request->requester->name }}')"
                                                                class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                                Leave Review
                                                            </button>
                                                        @endif

                                                        @if ($request->isAccepted())
                                                            <div class="mt-3">
                                                                <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                                    Contact via WhatsApp
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- IF BUKAN HELPER --}}
            <!-- Sent Requests Tab -->
            @if (auth()->user()->role !== 'helper')
                <div id="sent-content" class="sr-tab-content {{ $defaultTab === 'sent' ? '' : 'hidden' }}"
                    style="{{ $defaultTab === 'sent' ? 'display:block' : 'display:none' }}">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4">My Service Requests ({{ $sentRequests->count() }} total)
                            </h3>
                            <!-- Search Bar -->
                            <div class="mb-4">
                                <input type="text" id="request-search" placeholder="Search requests..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-teal focus:border-custom-teal">
                            </div>


                            <!-- Tabs for Pending, In Progress, Completed -->
                            <div class="mb-6">
                                <div class="flex space-x-4 border-b border-gray-200">
                                    <button onclick="showStatusTab('pending')" id="pending-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        Pending Request
                                    </button>
                                    <button onclick="showStatusTab('in-progress')" id="in-progress-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        In Progress
                                    </button>
                                    <button onclick="showStatusTab('completed')" id="completed-tab"
                                        class="sr-status-tab-button py-2 px-4 text-sm font-medium text-gray-700 hover:text-custom-teal focus:outline-none">
                                        Completed
                                    </button>
                                </div>
                            </div>

                            <!-- Pending Requests User Content -->
                            <div id="pending-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'pending' ? '' : 'hidden' }}">
                                @if ($sentRequests->where('status', 'pending')->isEmpty())
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.906-1.471c-.905-.405-1.967-.405-2.872 0L3.05 19.471c-.71.315-1.471-.215-1.471-.971V10.5c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No service requests</h3>
                                        <p class="mt-1 text-sm text-gray-500">You haven't sent any service requests yet.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('services.index') }}"
                                                class="inline-flex items-center px-4 py-2 shadow-sm text-sm font-medium rounded-md
                                                    bg-custom-teal text-white border border-transparent
                                                    hover:bg-white hover:text-custom-teal hover:border-custom-teal transition">
                                                Browse Services
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($sentRequests->where('status', 'pending') as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                            <strong>Provider: </strong> {{ $request->provider->name }}
                                                        </p>

                                                          @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Your message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                                @if ($request->studentService && !is_null($request->studentService->suggested_price))
                                                                    <span class="text-xs text-gray-500">(Suggested: RM
                                                                        {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                                @endif
                                                            </p>
                                                        @endif

                                                      

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>

                                                    <div class="flex space-x-2">
                                                        @if ($request->isPending())
                                                            <button onclick="cancelRequest({{ $request->id }})"
                                                                class="px-4 py-1.5 text-sm font-semibold text-red-600 hover:text-white border border-red-500 hover:bg-red-600 transition-all duration-200 rounded-lg shadow-sm">
                                                                Cancel
                                                            </button>

                                                            <form id="cancel-form-{{ $request->id }}"
                                                                action="{{ route('service-requests.cancel', $request->id) }}"
                                                                method="POST" class="hidden">
                                                                @csrf
                                                                @method('PATCH')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- In Progress Content -->
                            <div id="in-progress-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'in_progress' ? '' : 'hidden' }}">
                                @if ($sentRequests->whereIn('status', ['accepted', 'in_progress'])->isEmpty())
                                    <div class="text-center py-8">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Ongoing Services</h3>
                                        <p class="mt-1 text-sm text-gray-500">You have no ongoing services.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($sentRequests->whereIn('status', ['accepted', 'in_progress']) as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 sr-request-item">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                            From: {{ $request->requester->name }}
                                                        </p>

                                                          @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                                @if ($request->studentService && !is_null($request->studentService->suggested_price))
                                                                    <span class="text-xs text-gray-500">(Suggested: RM
                                                                        {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                                @endif
                                                            </p>
                                                        @endif

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-2">

                                                        @if ($request->isAccepted())
                                                            <!-- WhatsApp Contact Button -->
                                                            <div class="mt-3">
                                                                <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center px-4 py-2 rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-all duration-200">
                                                                    Contact via WhatsApp
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Completed Content -->
                            <div id="completed-content"
                                class="sr-status-tab-content {{ $defaultStatusTab === 'completed' ? '' : 'hidden' }}">
                                @if ($sentRequests->whereIn('status', ['completed', 'cancelled', 'rejected'])->isEmpty())
                                    <div class="text-center py-8">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Completed Services</h3>
                                        <p class="mt-1 text-sm text-gray-500">You have no completed service requests.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($sentRequests->whereIn('status', ['completed', 'cancelled', 'rejected']) as $request)
                                            <div class="border border-gray-200 rounded-lg p-4 sr-request-item">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="text-lg font-medium text-gray-900">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                                {{ $request->formatted_status }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600 mb-2">
                                                           <strong>From:</strong> {{ $request->requester->name }}
                                                        </p>

                                                        @if ($request->selected_dates)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Date request:</strong> {{ \Carbon\Carbon::parse($request->selected_dates)->format('j-n-Y') }}
                                                            </p>
                                                        @endif

                                                          @if ($request->selected_package)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Package:</strong> {{ $request->selected_package }}
                                                            </p>
                                                        @endif

                                                        @if ($request->message)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Message:</strong> {{ $request->message }}
                                                            </p>
                                                        @endif

                                                        @if ($request->offered_price)
                                                            <p class="text-sm text-gray-700 mb-2">
                                                                <strong>Offered price:</strong> RM
                                                                {{ number_format($request->offered_price, 2) }}
                                                                @if ($request->studentService && !is_null($request->studentService->suggested_price))
                                                                    <span class="text-xs text-gray-500">(Suggested: RM
                                                                        {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                                @endif
                                                            </p>
                                                        @endif

                                                        <p class="text-xs text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('service-requests.show', $request) }}"
                                                                class="text-custom-teal hover:text-indigo-800 text-sm font-medium">View
                                                                Details</a>
                                                        </div>
                                                    </div>

                                                    <div class="flex space-x-2">
                                                        @if (
                                                            $request->isCompleted() &&
                                                                !$request->reviews()->where('reviewer_id', auth()->id())->exists())
                                                            <button
                                                                onclick="openReviewModal({{ $request->id }}, '{{ $request->requester->name }}')"
                                                                class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                                Leave Review
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Leave a Review</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="reviewForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="reviewServiceRequestId" name="service_request_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rating
                        </label>
                        <div class="flex space-x-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})"
                                    class="star-button text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" id="rating" name="rating" required>
                    </div>

                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Comment (Optional)
                        </label>
                        <textarea id="comment" name="comment" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Share your experience..."></textarea>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeReviewModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize default tab based on user role
            showTab('{{ $defaultTab }}');
            showStatusTab('{{ $defaultStatusTab }}');
        });

        // Function to show the correct tab (Sent/Received Requests)
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.sr-tab-content').forEach(content => {
                content.classList.add('hidden');
                content.style.display = 'none';
            });

            // Remove active styles from all tabs
            document.querySelectorAll('.sr-tab-button').forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            const target = document.getElementById(tabName + '-content');
            target.classList.remove('hidden');
            target.style.display = '';

            // Add active styles to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        // Function to show the correct status tab (Pending, In Progress, Completed)
        function showStatusTab(status) {
            // Hide all status tab contents
            document.querySelectorAll('.sr-status-tab-content').forEach(content => {
                content.classList.add('hidden');
                content.style.display = 'none';
            });

            // Remove active styles from all status tabs
            document.querySelectorAll('.sr-status-tab-button').forEach(button => {
                button.classList.remove('text-indigo-600');
                button.classList.add('text-gray-500');
            });

            // Show selected status tab content
            const target = document.getElementById(status + '-content');
            target.classList.remove('hidden');
            target.style.display = '';

            // Add active styles to selected status tab
            const activeTab = document.getElementById(status + '-tab');
            activeTab.classList.remove('text-gray-500');
            activeTab.classList.add('text-indigo-600');
        }

        // Function to accept a request
        async function acceptRequest(requestId) {
            await updateRequestStatus(requestId, 'accept', 'Accepting request...');
        }

        // Function to reject a request
        async function rejectRequest(requestId) {
            await updateRequestStatus(requestId, 'reject', 'Rejecting request...');
        }

        // Function to mark a request as in progress
        async function markInProgress(requestId) {
            await updateRequestStatus(requestId, 'in-progress', 'Starting work...');
        }

        // Function to mark a request as completed
        async function markCompleted(requestId) {
            await updateRequestStatus(requestId, 'complete', 'Marking as completed...');
        }

        // Function to cancel a request
        async function cancelRequest(requestId) {
            await updateRequestStatus(requestId, 'cancel', 'Cancelling request...');
        }

        // Helper function to update request status
        async function updateRequestStatus(requestId, action, loadingText) {
            try {
                const endpointMap = {
                    'accept': `/service-requests/${requestId}/accept`,
                    'reject': `/service-requests/${requestId}/reject`,
                    'in-progress': `/service-requests/${requestId}/mark-in-progress`,
                    'complete': `/service-requests/${requestId}/mark-completed`,
                    'cancel': `/service-requests/${requestId}/cancel`
                };
                const url = endpointMap[action];
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    location.reload(); // Reload to reflect status change
                } else {
                    throw new Error(data.error || 'Failed to update request');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Function to open review modal
        function openReviewModal(serviceRequestId, userName) {
            document.getElementById('reviewServiceRequestId').value = serviceRequestId;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        // Function to close review modal
        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewForm').reset();
            resetStars();
        }

        // Function to set rating for the review
        function setRating(rating) {
            document.getElementById('rating').value = rating;

            // Update star display
            document.querySelectorAll('.star-button').forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // Function to reset the stars after review submission
        function resetStars() {
            document.querySelectorAll('.star-button').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('rating').value = '';
        }

        // Handling review form submission
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;

            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

            try {
                const response = await fetch('/reviews', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    closeReviewModal();
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to submit review');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });

        // BUTTON ACTION
        function cancelRequest(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This request will be marked as CANCELED.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, cancel it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch(`/service-requests/${id}/cancel`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            },
                        })
                        .then(response => {
                            if (!response.ok) throw new Error("Network response was not ok");
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire("Canceled!", "Request has been canceled.", "success")
                                .then(() => location.reload());
                        })
                        .catch(error => {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        });

                }
            });
        }


        function acceptRequest(id) {
            Swal.fire({
                title: "Accept this request?",
                text: "You can start working on it after accepting.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#16a34a",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, accept",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById("accept-form-" + id);
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Accepted!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // redirect ke tab in_progress
                                    window.location.href =
                                        "{{ route('service-requests.index') }}?tab=in_progress";
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        });
                }
            });
        }


        function rejectRequest(id) {
            Swal.fire({
                title: "Reject this request?",
                text: "The requester will be notified.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, reject",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('reject-form-' + id);
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Rejected!",
                                    text: data.message,
                                    icon: "error",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // redirect ke tab pending atau sesuai
                                    window.location.href =
                                        "{{ route('service-requests.index') }}?tab=pending";
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        });
                }
            });
        }


        function markInProgress(id) {
            Swal.fire({
                title: "Start working on this request?",
                text: "The requester will see that you have begun.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#2563eb",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, start",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('progress-form-' + id);
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "In Progress!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Redirect ke tab in_progress
                                    window.location.href =
                                        "{{ route('service-requests.index') }}?tab=in_progress";
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        });
                }
            });
        }

        function markCompleted(id) {
            Swal.fire({
                title: "Mark this request as completed?",
                text: "The requester will be notified that the work is done.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#4f46e5",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, complete it",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('completed-form-' + id);
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Completed!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Redirect terus ke tab completed
                                    window.location.href =
                                        "{{ route('service-requests.index') }}?tab=completed";
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        });
                }
            });
        }

        // SEARCH FUNCTION
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('request-search');
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const items = document.querySelectorAll('.sr-status-tab-content .sr-request-item');

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
