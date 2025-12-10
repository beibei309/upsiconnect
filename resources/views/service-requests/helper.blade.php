@extends('layouts.helper')

@section('content')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Requests (Helper View)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php
                // Default status tab is 'pending'
                $defaultStatusTab = request('tab', 'pending');
            @endphp

            <div id="received-content" class="sr-tab-content">
                <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">My Services Orders ({{ $receivedRequests->count() }} total)</h3>

                        <div class="mb-4">
                            <input type="text" id="request-search" placeholder="Search requests..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-custom-teal focus:border-custom-teal">
                        </div>

                        <div class="mb-6">
                            <div class="flex space-x-4 border-b border-gray-200">
                                <button onclick="showStatusTab('pending')" id="pending-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'pending' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    Pending Request
                                </button>
                                <button onclick="showStatusTab('in-progress')" id="in-progress-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'in-progress' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    In Progress
                                </button>
                                <button onclick="showStatusTab('completed')" id="completed-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'completed' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    Completed
                                </button>
                            </div>
                        </div>

                        <div id="pending-content"
                            class="sr-status-tab-content {{ $defaultStatusTab === 'pending' ? '' : 'hidden' }}">
                            @if ($receivedRequests->where('status', 'pending')->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">You have no pending service requests.</p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($receivedRequests->where('status', 'pending') as $request)
                                        @php
                                            $service = $request->studentService;
                                            // Determine which package fields to show based on selection (default to basic if custom)
                                            $pkgType = strtolower($request->selected_package ?? 'basic');

                                            // Safe access using variable variables
                                            $pkgDescription = $service->{$pkgType . '_description'} ?? null;
                                            $pkgDuration = $service->{$pkgType . '_duration'} ?? null;
                                            $pkgFrequency = $service->{$pkgType . '_frequency'} ?? null;
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 transition-all duration-300 hover:shadow-lg hover:border-indigo-100 sr-request-item">

                                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $request->status_color }}">
                                            </div>

                                            <div class="flex flex-col gap-6 md:flex-row">

                                                <div class="flex-1 space-y-4 pl-2">

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4
                                                                class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                                                <div
                                                                    class="flex items-center gap-1 rounded-full bg-white px-2 py-0.5 border border-gray-200 shadow-sm">
                                                                    <svg class="h-3 w-3 text-gray-400" fill="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path
                                                                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                                    </svg>
                                                                    <span
                                                                        class="font-semibold text-gray-700">{{ $request->requester->name }}</span>
                                                                </div>
                                                                <span class="text-xs">•
                                                                    {{ $request->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="inline-flex items-center rounded-full border border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-yellow-700 shadow-sm">
                                                            {{ $request->formatted_status }}
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="rounded-lg border border-gray-200 bg-white/50 p-4 shadow-sm backdrop-blur-sm">
                                                        <div
                                                            class="mb-3 flex items-center justify-between border-b border-gray-100 pb-2">
                                                            <span
                                                                class="text-sm font-bold text-indigo-900 uppercase tracking-wider">
                                                                {{ $request->selected_package ?? 'Custom' }} Package
                                                            </span>
                                                            @if ($request->offered_price)
                                                                <span class="text-lg font-bold text-green-600">
                                                                    RM {{ number_format($request->offered_price, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        @if ($pkgDescription)
                                                            <p class="mb-3 text-sm leading-relaxed text-gray-600">
                                                                {{ $pkgDescription }}
                                                            </p>
                                                        @endif

                                                        <div class="flex flex-wrap gap-2">
                                                            @if ($pkgDuration)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ $pkgDuration }}
                                                                    Hour{{ $pkgDuration > 1 ? 's' : '' }}
                                                                </span>
                                                            @endif

                                                            @if ($pkgFrequency)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                    </svg>
                                                                    {{ ucfirst($pkgFrequency) }}
                                                                </span>
                                                            @endif

                                                            @if ($request->selected_dates)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                                                    <svg class="h-3.5 w-3.5 text-blue-500" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    {{ \Carbon\Carbon::parse($request->selected_dates)->format('M j, Y') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if ($request->message)
                                                        <div
                                                            class="relative rounded-md bg-gray-50 p-3 pl-4 text-sm text-gray-600 italic">
                                                            <div
                                                                class="absolute left-0 top-0 bottom-0 w-1 rounded-l-md bg-gray-300">
                                                            </div>
                                                            "{{ $request->message }}"
                                                        </div>
                                                    @endif

                                                    <div class="pt-1">
                                                        <a href="{{ route('service-requests.show', $request) }}"
                                                            class="group/link inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                                            View Full Request Details
                                                            <svg class="ml-1 h-4 w-4 transition-transform group-hover/link:translate-x-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex min-w-[140px] flex-col justify-between gap-3 border-t border-gray-100 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">
                                   <div class="space-y-2">
    <span class="block text-xs font-semibold uppercase text-gray-400">Actions</span>

    

    <button onclick="acceptRequest({{ $request->id }})" 
        class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-700 hover:shadow-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Accept
    </button>
    <form id="accept-form-{{ $request->id }}" action="{{ route('service-requests.accept', $request->id) }}" method="POST" class="hidden">@csrf</form>

    <button onclick="rejectRequest({{ $request->id }})" 
        class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Reject
    </button>

     <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                        target="_blank"
                                                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                        </svg>
                                                        WhatsApp
                                                    </a>
    <form id="reject-form-{{ $request->id }}" action="{{ route('service-requests.reject', $request->id) }}" method="POST" class="hidden">@csrf</form>
</div>

                                                    <div class="text-center">
                                                        <span class="text-[10px] text-gray-400">ID:
                                                            #{{ $request->id }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div id="in-progress-content"
                            class="sr-status-tab-content {{ $defaultStatusTab === 'in-progress' ? '' : 'hidden' }}">
                            @if ($receivedRequests->whereIn('status', ['accepted', 'in_progress'])->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Ongoing Requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">You have no ongoing service requests.</p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($receivedRequests->whereIn('status', ['accepted', 'in_progress']) as $request)
                                        @php
                                            $service = $request->studentService;
                                            $pkgType = strtolower($request->selected_package ?? 'basic');
                                            $pkgDescription = $service->{$pkgType . '_description'} ?? null;
                                            $pkgDuration = $service->{$pkgType . '_duration'} ?? null;
                                            $pkgFrequency = $service->{$pkgType . '_frequency'} ?? null;
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border border-blue-100 bg-white p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:border-blue-200 sr-request-item">

                                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>

                                            <div class="flex flex-col gap-6 md:flex-row">

                                                <div class="flex-1 space-y-4 pl-3">

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4
                                                                class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <div
                                                                class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                                                <span class="font-medium text-gray-700">Client:
                                                                    {{ $request->requester->name }}</span>
                                                                <span class="text-gray-300">|</span>
                                                                <span class="text-xs">Started
                                                                    {{ $request->updated_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>

                                                        <span
                                                            class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700 shadow-sm animate-pulse">
                                                            <span class="mr-1.5 h-2 w-2 rounded-full bg-blue-500"></span>
                                                            {{ $request->formatted_status }}
                                                        </span>
                                                    </div>

                                                    <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4">
                                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                                            <div class="flex items-center gap-1.5">
                                                                <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                                </svg>
                                                                <span
                                                                    class="font-semibold text-gray-800">{{ ucfirst($request->selected_package ?? 'Custom') }}</span>
                                                            </div>

                                                            @if ($request->offered_price)
                                                                <div class="flex items-center gap-1.5">
                                                                    <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <span class="font-bold text-green-600">RM
                                                                        {{ number_format($request->offered_price, 2) }}</span>
                                                                </div>
                                                            @endif

                                                            @if ($request->selected_dates)
                                                                <div class="flex items-center gap-1.5">
                                                                    <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <span>{{ \Carbon\Carbon::parse($request->selected_dates)->format('M j, Y') }}</span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if ($pkgDescription)
                                                            <p
                                                                class="text-xs text-gray-500 line-clamp-2 border-t border-gray-200 pt-2 mt-2">
                                                                {{ $pkgDescription }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <div class="pt-1">
                                                        <a href="{{ route('service-requests.show', $request) }}"
                                                            class="group/link inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                                                            View Full Details
                                                            <svg class="ml-1 h-4 w-4 transition-transform group-hover/link:translate-x-1"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex min-w-[140px] flex-col justify-center gap-3 border-t border-gray-100 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">

                                                    <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                        target="_blank"
                                                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                        </svg>
                                                        WhatsApp
                                                    </a>

                                                    @if ($request->isAccepted())
                                                        <button onclick="markInProgress({{ $request->id }})"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-blue-600 bg-white px-4 py-2.5 text-sm font-semibold text-blue-600 shadow-sm transition-all hover:bg-blue-50 hover:shadow-md focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Start Work
                                                        </button>
                                                        <form id="progress-form-{{ $request->id }}"
                                                            action="{{ route('service-requests.mark-in-progress', $request->id) }}"
                                                            method="POST" class="hidden">@csrf</form>
                                                    @endif

                                                    @if ($request->isInProgress())
                                                        <button onclick="markCompleted({{ $request->id }})"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-indigo-700 hover:shadow-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Complete
                                                        </button>
                                                        <form id="completed-form-{{ $request->id }}"
                                                            action="{{ route('service-requests.mark-completed', $request->id) }}"
                                                            method="POST" class="hidden">@csrf</form>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div id="completed-content"
                            class="sr-status-tab-content {{ $defaultStatusTab === 'completed' ? '' : 'hidden' }}">
                            @if ($receivedRequests->whereIn('status', ['completed', 'cancelled', 'rejected'])->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Completed Requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">You have no completed service requests.</p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($receivedRequests->whereIn('status', ['completed', 'cancelled', 'rejected']) as $request)
                                        @php
                                            $service = $request->studentService;
                                            $pkgType = strtolower($request->selected_package ?? 'basic');
                                            $pkgDescription = $service->{$pkgType . '_description'} ?? null;
                                            $pkgDuration = $service->{$pkgType . '_duration'} ?? null;
                                            $pkgFrequency = $service->{$pkgType . '_frequency'} ?? null;

                                            // Determine border/text colors based on status for history view
                                            $statusColor = match ($request->status) {
                                                'completed' => 'bg-green-500',
                                                'cancelled' => 'bg-gray-400',
                                                'rejected' => 'bg-red-500',
                                                default => 'bg-gray-300',
                                            };
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6 opacity-85 transition-all duration-300 hover:opacity-100 hover:shadow-md sr-request-item">

                                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $statusColor }}"></div>

                                            <div class="flex flex-col gap-6 md:flex-row">

                                                <div class="flex-1 space-y-4 pl-3">

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4 class="text-lg font-bold text-gray-800">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <div
                                                                class="mt-1 flex items-center gap-2 text-sm text-gray-400">
                                                                <span class="font-medium">Client:
                                                                    {{ $request->requester->name }}</span>
                                                                <span class="text-gray-300">|</span>
                                                                <span class="text-xs">
                                                                    @if ($request->status === 'completed')
                                                                        Completed
                                                                        {{ $request->updated_at->format('M j, Y') }}
                                                                    @else
                                                                        Updated {{ $request->updated_at->diffForHumans() }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <span
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide {{ $request->status_color }}">
                                                            {{ $request->formatted_status }}
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="flex flex-wrap items-center gap-3 text-sm text-gray-500 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                                        <div class="flex items-center gap-1.5">
                                                            <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                            <span
                                                                class="font-medium">{{ ucfirst($request->selected_package ?? 'Custom') }}</span>
                                                        </div>

                                                        @if ($request->offered_price)
                                                            <div class="flex items-center gap-1.5 text-gray-700">
                                                                <span class="font-bold">RM
                                                                    {{ number_format($request->offered_price, 2) }}</span>
                                                            </div>
                                                        @endif

                                                        @if ($request->selected_dates)
                                                            <div class="flex items-center gap-1.5 text-xs">
                                                                <svg class="h-3.5 w-3.5 text-gray-400" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                {{ \Carbon\Carbon::parse($request->selected_dates)->format('d M Y') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="pt-1">
                                                        <a href="{{ route('service-requests.show', $request) }}"
                                                            class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
                                                            View History Details
                                                            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </a>
                                                    </div>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Tab Switching Logic
        function showStatusTab(status) {
            // Hide all tab contents
            document.querySelectorAll('.sr-status-tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Reset all tab buttons
            document.querySelectorAll('.sr-status-tab-button').forEach(button => {
                button.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                button.classList.add('text-gray-500', 'hover:text-custom-teal');
            });

            // Show active content
            const targetContent = document.getElementById(status + '-content');
            if (targetContent) targetContent.classList.remove('hidden');

            // Highlight active button
            const targetButton = document.getElementById(status + '-tab');
            if (targetButton) {
                targetButton.classList.remove('text-gray-500', 'hover:text-custom-teal');
                targetButton.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
            }
        }

        // Search function
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('request-search');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();

                    // Select all request cards across all tabs
                    const items = document.querySelectorAll('.sr-request-item');

                    items.forEach(item => {
                        // Get the text content of the card (Title, Name, Status, etc.)
                        const text = item.textContent.toLowerCase();

                        // Show or Hide based on match
                        if (text.includes(query)) {
                            item.style.display = ''; // Reset to default (show)
                        } else {
                            item.style.display = 'none'; // Hide
                        }
                    });
                });
            }
        });

        // 2. Action Triggers
        async function acceptRequest(id) {
            confirmAction(id, 'accept', 'Accept Request?', 'You can start working on this service once accepted.',
                '#16a34a', 'Yes, Accept');
        }

        async function rejectRequest(id) {
            confirmAction(id, 'reject', 'Reject Request?', 'The requester will be notified.', '#dc2626', 'Yes, Reject');
        }

        async function markInProgress(id) {
            confirmAction(id, 'progress', 'Start Work?', 'The status will change to In Progress.', '#2563eb',
                'Yes, Start');
        }

        async function markCompleted(id) {
            confirmAction(id, 'completed', 'Mark as Completed?', 'This will notify the client the work is done.',
                '#4f46e5', 'Yes, Complete');
        }

        // 3. Reusable SweetAlert & Fetch Logic
        function confirmAction(id, type, title, text, confirmColor, confirmText) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find the hidden form associated with the button
                    // Note: Ensure your forms have IDs like "accept-form-123"
                    const formId = `${type}-form-${id}`;
                    const form = document.getElementById(formId);

                    if (!form) {
                        Swal.fire('Error', 'Form not found for this action.', 'error');
                        return;
                    }

                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // Send AJAX Request
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // Reload page to update the lists
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'System error occurred. Please try again.', 'error');
                        });
                }
            });
        }

        // 4. Initialize Tabs on Load
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL for 'tab' parameter or default to 'pending'
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || '{{ $defaultStatusTab }}';
            showStatusTab(activeTab);
        });
    </script>
@endsection
