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
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
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
                                                               {{ str_replace('"', '', $request->selected_package) ?? 'Custom' }} Package

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
                                                                    @php
                                                                        $dates = $request->selected_dates;
                                                                        $firstDate = is_array($dates)
                                                                            ? $dates[0]
                                                                            : $dates;
                                                                        $count = is_array($dates) ? count($dates) : 1;
                                                                    @endphp
                                                                    {{ \Carbon\Carbon::parse($firstDate)->format('M j, Y') }}
                                                                    @if ($count > 1)
                                                                        <span class="ml-1">(+{{ $count - 1 }})</span>
                                                                    @endif
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
                                                        <span
                                                            class="block text-xs font-semibold uppercase text-gray-400">Actions</span>



                                                        <button onclick="acceptRequest({{ $request->id }})"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-700 hover:shadow-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Accept
                                                        </button>
                                                        <form id="accept-form-{{ $request->id }}"
                                                            action="{{ route('service-requests.accept', $request->id) }}"
                                                            method="POST" class="hidden">@csrf</form>

                                                        <button type="button" onclick="openRejectModal({{ $request->id }})"
    class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
    Reject
</button>

<form id="reject-form-{{ $request->id }}" 
      action="{{ route('service-requests.reject', $request->id) }}" 
      method="POST" style="display: none;">
    @csrf
    </form>

                                                        <a href="https://wa.me/6{{ $request->requester->phone }}"
                                                            target="_blank"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                            </svg>
                                                            WhatsApp
                                                        </a>
                                                        <form id="reject-form-{{ $request->id }}"
                                                            action="{{ route('service-requests.reject', $request->id) }}"
                                                            method="POST" class="hidden">@csrf</form>
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
                                                                    class="font-semibold text-gray-800">{{ ucfirst(trim($request->selected_package, '"') ?? 'Custom') }}</span>
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
                                                                    @php
                                                                        $dates = $request->selected_dates;
                                                                        $firstDate = is_array($dates)
                                                                            ? $dates[0]
                                                                            : $dates;
                                                                        $count = is_array($dates) ? count($dates) : 1;
                                                                    @endphp
                                                                    <span>{{ \Carbon\Carbon::parse($firstDate)->format('M j, Y') }}</span>
                                                                    @if ($count > 1)
                                                                        <span
                                                                            class="ml-1 text-xs">(+{{ $count - 1 }})</span>
                                                                    @endif
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
                                {{-- Empty State --}}
                                <div
                                    class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50">
                                    <div class="rounded-full bg-gray-100 p-4 mb-3">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900">No Completed Requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">You don't have any past history yet.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 gap-6">
                                    @foreach ($receivedRequests->whereIn('status', ['completed', 'cancelled', 'rejected']) as $request)
                                        @php
                                            // Status Styling Logic
                                            $statusStyles = match ($request->status) {
                                                'completed' => [
                                                    'bg' => 'bg-green-50',
                                                    'text' => 'text-green-700',
                                                    'dot' => 'bg-green-500',
                                                    'border' => 'border-green-100',
                                                ],
                                                'cancelled' => [
                                                    'bg' => 'bg-gray-100',
                                                    'text' => 'text-gray-700',
                                                    'dot' => 'bg-gray-500',
                                                    'border' => 'border-gray-200',
                                                ],
                                                'rejected' => [
                                                    'bg' => 'bg-red-50',
                                                    'text' => 'text-red-700',
                                                    'dot' => 'bg-red-500',
                                                    'border' => 'border-red-100',
                                                ],
                                                default => [
                                                    'bg' => 'bg-gray-50',
                                                    'text' => 'text-gray-600',
                                                    'dot' => 'bg-gray-400',
                                                    'border' => 'border-gray-200',
                                                ],
                                            };
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border {{ $statusStyles['border'] }} bg-white shadow-sm transition-all duration-200 hover:shadow-md">

                                            {{-- Card Header: Service & Status --}}
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-5 pb-3 gap-4">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="text-lg font-bold text-gray-900 line-clamp-1">
                                                            {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                        </h4>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <span class="font-medium text-gray-700 mr-1">Client:</span>
                                                        {{ $request->requester->name }}
                                                    </div>
                                                </div>

                                                {{-- Status Pill --}}
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles['bg'] }} {{ $statusStyles['text'] }}">
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </div>

                                            <hr class="border-gray-100 mx-5">

                                            {{-- Card Body: Details Grid --}}
                                            <div class="p-5 pt-3 grid grid-cols-1 md:grid-cols-2 gap-4">

                                                {{-- Left Column: Request Meta --}}
                                                <div class="space-y-3">
                                                    {{-- Package --}}
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <div
                                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mr-3">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p
                                                                class="text-xs text-gray-400 uppercase font-bold tracking-wider">
                                                                Package</p>
                                                            <p class="font-medium text-gray-900">
                                                                {{ ucfirst(trim($request->selected_package, '"') ?? 'Custom') }}</p>
                                                        </div>
                                                    </div>

                                                    {{-- Price --}}
                                                    @if ($request->offered_price)
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <div
                                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 mr-3">
                                                                <span class="font-bold text-xs">RM</span>
                                                            </div>
                                                            <div>
                                                                <p
                                                                    class="text-xs text-gray-400 uppercase font-bold tracking-wider">
                                                                    Total Price</p>
                                                                <p class="font-medium text-gray-900">RM
                                                                    {{ number_format($request->offered_price, 2) }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Right Column: Dates & Actions --}}
                                                <div class="space-y-3">
                                                    {{-- Date Section --}}
                                                    @if ($request->selected_dates)
                                                        {{-- 1. Paparkan TARIKH Dulu --}}
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <div
                                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-orange-600 mr-3">
                                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p
                                                                    class="text-xs text-gray-400 uppercase font-bold tracking-wider">
                                                                    Date</p>
                                                                <p class="font-medium text-gray-900">
                                                                    @php
                                                                        $dates = $request->selected_dates;
                                                                        $firstDate = is_array($dates)
                                                                            ? $dates[0]
                                                                            : $dates;
                                                                        $count = is_array($dates) ? count($dates) : 1;
                                                                    @endphp
                                                                    {{ \Carbon\Carbon::parse($firstDate)->format('d M Y') }}
                                                                    @if ($count > 1)
                                                                        <span
                                                                            class="text-gray-400 text-xs">(+{{ $count - 1 }}
                                                                            more)</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>

                                                        {{-- 2. Paparkan DURATION di Bawah Tarikh --}}
                                                        @if ($request->status === 'completed' && $request->started_at && $request->completed_at)
                                                            <div class="pt-3 mt-3 border-t border-gray-100">
                                                                <div class="flex items-start gap-3">
                                                                    {{-- Icon Jam --}}
                                                                    <div
                                                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 shrink-0">
                                                                        <svg class="h-4 w-4" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                    </div>

                                                                    <div class="flex-1">
                                                                        <p
                                                                            class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">
                                                                            Work Duration</p>

                                                                        <div class="grid grid-cols-2 gap-2">
                                                                            {{-- Start Time --}}
                                                                            <div>
                                                                                <span
                                                                                    class="text-[10px] text-gray-500 block">Started:</span>
                                                                                <span
                                                                                    class="text-xs font-semibold text-gray-900">
                                                                                    {{ $request->started_at->format('h:i A') }}
                                                                                </span>
                                                                                <span
                                                                                    class="text-[10px] text-gray-400 block">
                                                                                    {{ $request->started_at->format('d M') }}
                                                                                </span>
                                                                            </div>

                                                                            {{-- End Time --}}
                                                                            <div>
                                                                                <span
                                                                                    class="text-[10px] text-gray-500 block">Finished:</span>
                                                                                <span
                                                                                    class="text-xs font-semibold text-gray-900">
                                                                                    {{ $request->completed_at->format('h:i A') }}
                                                                                </span>
                                                                                <span
                                                                                    class="text-[10px] text-gray-400 block">
                                                                                    {{ $request->completed_at->format('d M') }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Footer: Actions --}}
                                            <div
                                                class="bg-gray-50 px-5 py-3 flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-gray-100">
                                                {{-- Link to Details --}}
                                                <a href="{{ route('service-requests.show', $request) }}"
                                                    class="text-sm text-gray-600 hover:text-indigo-600 font-medium flex items-center transition-colors">
                                                    View Full Details
                                                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>

                                                {{-- Review Action Logic --}}
                                                {{-- Review Action Logic --}}
                                                <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">

                                                    {{-- ⭐ CLIENT → SELLER REVIEW (Incoming) --}}
                                                    @if ($request->reviewForHelper)
                                                        <button
                                                            onclick='openReviewModal(@json($request->reviewForHelper), "{{ $request->requester->name }}")'
                                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 transition-all shadow-sm">
                                                            <div class="flex gap-0.5 text-yellow-500">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="{{ $i <= $request->reviewForHelper->rating ? 'fas' : 'far' }} fa-star text-xs"></i>
                                                                @endfor
                                                            </div>
                                                            <span class="ml-1">
                                                                {{ $request->reviewForHelper->reply ? 'See Reply' : 'Reply to Review' }}
                                                            </span>
                                                        </button>
                                                    @elseif($request->status === 'completed')
                                                        <div
                                                            class="inline-flex items-center gap-2 text-xs text-gray-400 bg-white px-3 py-1.5 rounded-md border border-gray-200">
                                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Waiting for client review
                                                        </div>
                                                    @endif

                                                    {{-- ⭐ SELLER → CLIENT REVIEW (Outgoing) --}}
                                                    @if ($request->reviewByHelper)
                                                        <span
                                                            class="inline-flex items-center justify-center gap-2 px-3 py-1.5 text-xs rounded-md bg-green-50 text-green-700 border border-green-200">
                                                            <i class="fas fa-check"></i> You reviewed this client
                                                        </span>
                                                    @elseif($request->status === 'completed')
                                                        {{-- FIXED: Button now calls the correct function --}}
                                                        <button onclick="openSellerReviewModal({{ $request->id }})"
                                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 border border-transparent transition-all shadow-sm">
                                                            <i class="fas fa-star"></i>
                                                            Rate Client
                                                        </button>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div id="reviewModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog"
                            aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                    <div
                                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                        {{-- Modal Header --}}
                                        <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-base font-semibold leading-6 text-white" id="modal-title">
                                                    Client Review</h3>
                                                <button type="button" onclick="closeReviewModal()"
                                                    class="text-indigo-200 hover:text-white">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            {{-- Client Review Box --}}
                                            <div class="rounded-xl bg-yellow-50 p-4 border border-yellow-100 mb-6">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-sm"
                                                            id="modalRequesterName">Client Name</h4>
                                                        <p class="text-xs text-gray-500" id="modalDate">Date</p>
                                                    </div>
                                                    <div class="text-yellow-400 text-sm flex gap-1" id="modalStars"></div>
                                                </div>
                                                <div class="relative mt-2">
                                                    <span
                                                        class="absolute top-0 left-0 text-yellow-200 text-4xl -translate-y-2 -translate-x-2">"</span>
                                                    <p class="relative text-sm text-gray-700 italic px-2 z-10"
                                                        id="modalComment"></p>
                                                </div>
                                            </div>

                                            {{-- Reply Section --}}
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-indigo-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    Your Response
                                                </h4>

                                                {{-- State A: Already Replied --}}
                                                <div id="viewReplyContainer" class="hidden">
                                                    <div
                                                        class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm text-gray-700 relative">
                                                        <p id="modalReplyText"></p>
                                                        <div class="mt-2 text-right">
                                                            <span class="text-xs text-gray-400">Replied on <span
                                                                    id="modalRepliedAt"></span></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- State B: Form --}}
                                                <form id="replyForm" method="POST" action="" class="hidden">
                                                    @csrf
                                                    <div class="relative">
                                                        <textarea name="reply" rows="4"
                                                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3"
                                                            placeholder="Thank the client for their feedback..."></textarea>
                                                    </div>
                                                    <div class="mt-4 flex justify-end">
                                                        <button type="submit"
                                                            class="inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                            Post Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sellerReviewModal" class="relative z-50 hidden" aria-labelledby="modal-title"
                            role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

                            <div class="fixed inset-0 z-10 overflow-y-auto">
                                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                    <div
                                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                        {{-- Modal Header --}}
                                        <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-base font-semibold leading-6 text-white">
                                                    Rate This Client
                                                </h3>
                                                <button type="button" onclick="closeSellerReviewModal()"
                                                    class="text-indigo-200 hover:text-white focus:outline-none">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Modal Body --}}
                                        <div class="px-4 pt-5 pb-4 sm:p-6">
                                            <form id="sellerReviewForm" onsubmit="submitSellerReview(event)">
                                                <input type="hidden" name="service_request_id"
                                                    id="sellerReviewRequestId">
                                                <input type="hidden" name="rating" id="sellerReviewRating">

                                                {{-- Star Rating Input --}}
                                                <div class="mb-6 text-center">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">How was
                                                        your experience?</label>
                                                    <div class="flex justify-center gap-2 text-2xl cursor-pointer">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="far fa-star text-gray-300 hover:text-yellow-400 transition-colors seller-star-input"
                                                                data-value="{{ $i }}"
                                                                onclick="setSellerRating({{ $i }})"></i>
                                                        @endfor
                                                    </div>
                                                    <p class="text-xs text-red-500 mt-1 hidden" id="ratingError">Please
                                                        select a rating.</p>
                                                </div>

                                                {{-- Comment Input --}}
                                                <div class="mb-4">
                                                    <label for="sellerComment"
                                                        class="block text-sm font-medium text-gray-700 mb-1">Comment
                                                        (Optional)</label>
                                                    <textarea id="sellerComment" name="comment" rows="4"
                                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-gray-400"
                                                        placeholder="Describe your experience working with this client..."></textarea>
                                                </div>

                                                {{-- Actions --}}
                                                <div
                                                    class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                                    <button type="submit"
                                                        class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">
                                                        Submit Review
                                                    </button>
                                                    <button type="button" onclick="closeSellerReviewModal()"
                                                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <script>
                            function openReviewModal(review, requesterName) {
                                // 1. Populate Client Review
                                document.getElementById('modalRequesterName').innerText = requesterName;
                                document.getElementById('modalComment').innerText = review.comment || 'No textual comment provided.';
                                document.getElementById('modalDate').innerText = new Date(review.created_at).toLocaleDateString(undefined, {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });

                                // Generate Stars
                                let starsHtml = '';
                                for (let i = 1; i <= 5; i++) {
                                    starsHtml += `<i class="${i <= review.rating ? 'fas' : 'far'} fa-star"></i>`;
                                }
                                document.getElementById('modalStars').innerHTML = starsHtml;

                                // 2. Handle Reply State
                                const replyForm = document.getElementById('replyForm');
                                const viewReplyContainer = document.getElementById('viewReplyContainer');

                                if (review.reply) {
                                    replyForm.classList.add('hidden');
                                    viewReplyContainer.classList.remove('hidden');
                                    document.getElementById('modalReplyText').innerText = review.reply;
                                    document.getElementById('modalRepliedAt').innerText = new Date(review.replied_at).toLocaleDateString();
                                } else {
                                    viewReplyContainer.classList.add('hidden');
                                    replyForm.classList.remove('hidden');
                                    // Ensure you have a named route like 'reviews.reply' that accepts the ID
                                    // If your route is resource based: /reviews/{id}/reply
                                    replyForm.action = `/reviews/${review.id}/reply`;
                                }

                                document.getElementById('reviewModal').classList.remove('hidden');
                            }

                            function closeReviewModal() {
                                document.getElementById('reviewModal').classList.add('hidden');
                            }

                            function openSellerReviewModal(requestId) {
                                // Reset form
                                document.getElementById('sellerReviewForm').reset();
                                document.getElementById('sellerReviewRequestId').value = requestId;
                                document.getElementById('sellerReviewRating').value = '';
                                document.getElementById('ratingError').classList.add('hidden');

                                // Reset Stars visual
                                document.querySelectorAll('.seller-star-input').forEach(star => {
                                    star.classList.remove('fas', 'text-yellow-400');
                                    star.classList.add('far', 'text-gray-300');
                                });

                                document.getElementById('sellerReviewModal').classList.remove('hidden');
                            }

                            function closeSellerReviewModal() {
                                document.getElementById('sellerReviewModal').classList.add('hidden');
                            }

                            function setSellerRating(rating) {
                                document.getElementById('sellerReviewRating').value = rating;
                                document.getElementById('ratingError').classList.add('hidden');

                                const stars = document.querySelectorAll('.seller-star-input');
                                stars.forEach(star => {
                                    const val = parseInt(star.getAttribute('data-value'));
                                    if (val <= rating) {
                                        star.classList.remove('far', 'text-gray-300');
                                        star.classList.add('fas', 'text-yellow-400');
                                    } else {
                                        star.classList.remove('fas', 'text-yellow-400');
                                        star.classList.add('far', 'text-gray-300');
                                    }
                                });
                            }

                            function submitSellerReview(event) {
                                event.preventDefault();
                                const rating = document.getElementById('sellerReviewRating').value;
                                const requestId = document.getElementById('sellerReviewRequestId').value;
                                const comment = document.getElementById('sellerComment').value;

                                if (!rating) {
                                    document.getElementById('ratingError').classList.remove('hidden');
                                    return;
                                }

                                Swal.fire({
                                    title: 'Submitting Review...',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });

                                // Send AJAX Request to your existing Controller
                                fetch("{{ route('reviews.store') }}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                            "Accept": "application/json"
                                        },
                                        body: JSON.stringify({
                                            service_request_id: requestId,
                                            rating: rating,
                                            comment: comment
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            closeSellerReviewModal();
                                            Swal.fire({
                                                title: 'Review Submitted!',
                                                text: 'Thank you for rating the client.',
                                                icon: 'success',
                                                timer: 1500,
                                                showConfirmButton: false
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire('Error', data.message || 'Could not submit review', 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                                    });
                            }

                            function openRejectModal(requestId) {
        Swal.fire({
            title: 'Reject Request?',
            text: "Please provide a reason for the requester.",
            input: 'textarea', // Creates a text box
            inputPlaceholder: 'e.g. I am fully booked on this date...',
            inputAttributes: {
                'aria-label': 'Type your rejection reason here'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Reject it',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('You must provide a reason!')
                }
                return reason; // Returns the text value
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // 1. Get the form
                const form = document.getElementById('reject-form-' + requestId);
                
                // 2. Create a hidden input for the reason
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'rejection_reason';
                input.value = result.value; // The text from SweetAlert
                
                // 3. Append and Submit
                form.appendChild(input);
                form.submit();
            }
        });
    }
                        </script>

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
