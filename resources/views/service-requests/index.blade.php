<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Service Requests') }}
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <br><br>

            @php
                $defaultStatusTab = request('tab', 'pending');
            @endphp

            <div id="sent-content" class="sr-tab-content">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-800">
                        <h3 class="font-medium mb-4 700" style="font-size: 25px;">My Order ({{ $sentRequests->count() }} total)</h3>

                       {{-- SEARCH & FILTER SECTION --}}
<div class="mb-6 flex flex-col md:flex-row gap-4">
    {{-- Search Bar --}}
    <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" id="request-search" 
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
            placeholder="Search by title, seller, etc...">
    </div>

    {{-- Category Filter Dropdown --}}
    <div class="w-full md:w-64">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </div>
            
            {{-- Extract unique categories from requests automatically --}}
            @php
                $uniqueCategories = $sentRequests->map(function($request) {
                    return optional($request->studentService->category)->name ?? 'Other';
                })->unique()->sort()->values();
            @endphp

            <select id="category-filter" 
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm appearance-none cursor-pointer">
                <option value="">All Categories</option>
                @foreach($uniqueCategories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            
            {{-- Custom chevron icon for select --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>
</div>

                        <div class="mb-6">
                            <div class="flex space-x-4 border-b border-gray-200">
                                <button onclick="showStatusTab('pending')" id="pending-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'pending' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    Pending
                                </button>
                                <button onclick="showStatusTab('in-progress')" id="in-progress-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'in-progress' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    In Progress
                                </button>
                                <button onclick="showStatusTab('completed')" id="completed-tab"
                                    class="sr-status-tab-button py-2 px-4 text-sm font-medium {{ $defaultStatusTab === 'completed' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-custom-teal' }} focus:outline-none">
                                    History
                                </button>
                            </div>
                        </div>

                        <div id="pending-content"
                            class="sr-status-tab-content {{ $defaultStatusTab === 'pending' ? '' : 'hidden' }}">
                            @if ($sentRequests->where('status', 'pending')->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">You haven't sent any service requests yet.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('services.index') }}"
                                            class="inline-flex items-center px-4 py-2 shadow-sm text-sm font-medium rounded-md bg-custom-teal text-white hover:bg-teal-700 transition">
                                            Browse Services
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($sentRequests->where('status', 'pending') as $request)
                                        @php
                                            $service = $request->studentService;
                                            $pkgType = strtolower($request->selected_package ?? 'basic');
                                            $pkgDescription = $service->{$pkgType . '_description'} ?? null;
                                            $pkgDuration = $service->{$pkgType . '_duration'} ?? null;
                                            $pkgFrequency = $service->{$pkgType . '_frequency'} ?? null;
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 transition-all duration-300 hover:shadow-lg hover:border-yellow-100">

                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>

                                            <div class="flex flex-col gap-6 md:flex-row">

                                                <div class="flex-1 space-y-4 pl-2">

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4
                                                                class="text-xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <div
                                                                class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                                                <span class="font-medium text-gray-700">Seller:
                                                                    {{ $request->provider->name }}</span>
                                                                <span class="text-gray-300">|</span>
                                                                <span class="text-xs">Sent
                                                                    {{ $request->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>

                                                        <span
                                                            class="inline-flex items-center rounded-full border border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-yellow-700 shadow-sm animate-pulse">
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
                                                                    <svg class="h-3.5 w-3.5 text-gray-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ $pkgDuration }}
                                                                    Hour{{ $pkgDuration > 1 ? 's' : '' }}
                                                                </span>
                                                            @endif

                                                            @if ($pkgFrequency)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                                    <svg class="h-3.5 w-3.5 text-gray-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                    </svg>
                                                                    {{ ucfirst($pkgFrequency) }}
                                                                </span>
                                                            @endif

                                                            @if ($request->selected_dates)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                                                    <svg class="h-3.5 w-3.5 text-blue-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    @php
                                                                        $dates = $request->selected_dates;
                                                                        $firstDate = is_array($dates) ? $dates[0] : $dates;
                                                                        $count = is_array($dates) ? count($dates) : 1;
                                                                    @endphp
                                                                    {{ \Carbon\Carbon::parse($firstDate)->format('M j, Y') }}
                                                                    @if($count > 1)
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
                                                            class="group/link inline-flex items-center text-sm font-semibold text-yellow-600 hover:text-yellow-800">
                                                            View Full Details
                                                            <svg class="ml-1 h-4 w-4 transition-transform group-hover/link:translate-x-1"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
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

                                                        <a href="https://wa.me/6{{ $request->provider->phone }}"
                                                            target="_blank"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                            </svg>
                                                            Contact
                                                        </a>

                                                        <button onclick="cancelRequest({{ $request->id }})"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Cancel Request
                                                        </button>
                                                        <form id="cancel-form-{{ $request->id }}"
                                                            action="{{ route('service-requests.cancel', $request->id) }}"
                                                            method="POST" class="hidden">@csrf</form>
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
                            @if ($sentRequests->whereIn('status', ['accepted', 'in_progress'])->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Ongoing Services</h3>
                                    <p class="mt-1 text-sm text-gray-500">You have no active services at the moment.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($sentRequests->whereIn('status', ['accepted', 'in_progress']) as $request)
                                        @php
                                            $service = $request->studentService;
                                            $pkgType = strtolower($request->selected_package ?? 'basic');
                                            $pkgDescription = $service->{$pkgType . '_description'} ?? null;
                                            $pkgDuration = $service->{$pkgType . '_duration'} ?? null;
                                            $pkgFrequency = $service->{$pkgType . '_frequency'} ?? null;
                                        @endphp

                                        <div
                                            class="group relative overflow-hidden rounded-xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 p-6 transition-all duration-300 hover:shadow-lg hover:border-blue-100">

                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>

                                            <div class="flex flex-col gap-6 md:flex-row">

                                                <div class="flex-1 space-y-4 pl-2">

                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <h4
                                                                class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                                {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                            </h4>
                                                            <div
                                                                class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                                                <span class="font-medium text-gray-700">Seller:
                                                                    {{ $request->provider->name }}</span>
                                                                <span class="text-gray-300">|</span>
                                                                <span class="text-xs">Started
                                                                    {{ $request->updated_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>

                                                        <span
                                                            class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-blue-700 shadow-sm animate-pulse">
                                                            <span
                                                                class="mr-1.5 h-2 w-2 rounded-full bg-blue-500"></span>
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
                                                                    <svg class="h-3.5 w-3.5 text-gray-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {{ $pkgDuration }}
                                                                    Hour{{ $pkgDuration > 1 ? 's' : '' }}
                                                                </span>
                                                            @endif

                                                            @if ($pkgFrequency)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                                                    <svg class="h-3.5 w-3.5 text-gray-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                    </svg>
                                                                    {{ ucfirst($pkgFrequency) }}
                                                                </span>
                                                            @endif

                                                            @if ($request->selected_dates)
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                                                    <svg class="h-3.5 w-3.5 text-blue-500"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                    @php
                                                                        $dates = $request->selected_dates;
                                                                        $firstDate = is_array($dates) ? $dates[0] : $dates;
                                                                        $count = is_array($dates) ? count($dates) : 1;
                                                                    @endphp
                                                                    {{ \Carbon\Carbon::parse($firstDate)->format('M j, Y') }}
                                                                    @if($count > 1)
                                                                        <span class="ml-1">(+{{ $count - 1 }})</span>
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="pt-1">
                                                        <a href="{{ route('service-requests.show', $request) }}"
                                                            class="group/link inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                                                            View Full Details
                                                            <svg class="ml-1 h-4 w-4 transition-transform group-hover/link:translate-x-1"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex min-w-[140px] flex-col justify-center gap-3 border-t border-gray-100 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">
                                                    <div class="space-y-2">
                                                        <span
                                                            class="block text-xs font-semibold uppercase text-gray-400">Actions</span>

                                                        <a href="https://wa.me/6{{ $request->provider->phone }}"
                                                            target="_blank"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-green-600 hover:shadow-lg hover:-translate-y-0.5 focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                                            </svg>
                                                            WhatsApp
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div id="completed-content"
                            class="sr-status-tab-content {{ $defaultStatusTab === 'completed' ? '' : 'hidden' }}">
                            @if ($sentRequests->whereIn('status', ['completed', 'cancelled', 'rejected'])->isEmpty())
                                <div class="text-center py-8">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No History</h3>
                                    <p class="mt-1 text-sm text-gray-500">You have no completed or cancelled requests.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($sentRequests->whereIn('status', ['completed', 'cancelled', 'rejected']) as $request)
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
                                            class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6 opacity-90 transition-all duration-300 hover:opacity-100 hover:shadow-md">

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
                                                                <span class="font-medium">Seller:
                                                                    {{ $request->provider->name }}</span>
                                                                <span class="text-gray-300">|</span>
                                                                <span class="text-xs">
                                                                    @if ($request->status === 'completed')
                                                                        Completed
                                                                        {{ $request->updated_at->format('M j, Y') }}
                                                                    @else
                                                                        Updated
                                                                        {{ $request->updated_at->diffForHumans() }}
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
                                                                @php
                                                                    $dates = $request->selected_dates;
                                                                    $firstDate = is_array($dates) ? $dates[0] : $dates;
                                                                    $count = is_array($dates) ? count($dates) : 1;
                                                                @endphp
                                                                {{ \Carbon\Carbon::parse($firstDate)->format('d M Y') }}
                                                                @if($count > 1)
                                                                    <span>(+{{ $count - 1 }})</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="pt-1">
                                                        <a href="{{ route('service-requests.show', $request) }}"
                                                            class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
                                                            View History Details
                                                            <svg class="ml-1 h-4 w-4" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                @if (
                                                    $request->isCompleted() &&
                                                        !$request->reviews()->where('reviewer_id', auth()->id())->exists())
                                                    <div
                                                        class="flex min-w-[140px] flex-col justify-center gap-3 border-t border-gray-100 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">
                                                        <button
                                                            onclick="openReviewModal({{ $request->id }}, '{{ $request->provider->name }}')"
                                                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-indigo-700 hover:shadow-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                            </svg>
                                                            Leave Review
                                                        </button>
                                                    </div>
                                                @endif

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

    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Rate & Review</h3>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-1 justify-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})"
                                    class="star-button text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors">★</button>
                            @endfor
                        </div>
                        <input type="hidden" id="rating" name="rating" required>
                    </div>

                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                        <textarea id="comment" name="comment" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="How was your experience?"></textarea>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeReviewModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showStatusTab('{{ $defaultStatusTab }}');
        });

        // Tab Switching
        function showStatusTab(status) {
            document.querySelectorAll('.sr-status-tab-content').forEach(content => content.classList.add('hidden'));
            document.querySelectorAll('.sr-status-tab-button').forEach(button => {
                button.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                button.classList.add('text-gray-500');
            });

            document.getElementById(status + '-content').classList.remove('hidden');
            const activeTab = document.getElementById(status + '-tab');
            activeTab.classList.remove('text-gray-500');
            activeTab.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
        }

        // Action: Cancel Request
        function cancelRequest(id) {
            Swal.fire({
                title: "Cancel Request?",
                text: "Are you sure you want to cancel this request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, cancel it"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('cancel-form-' + id);
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                            method: 'POST', // Laravel treats PATCH via POST usually, or use method: 'PATCH' if supported directly
                            body: new FormData(form),
                            headers: {
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Cancelled", "Your request has been cancelled.", "success").then(() =>
                                    location.reload());
                            } else {
                                Swal.fire("Error", data.message || "Could not cancel.", "error");
                            }
                        });
                }
            });
        }

        // Review Modal Logic
        function openReviewModal(id, name) {
            document.getElementById('reviewServiceRequestId').value = id;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewForm').reset();
            resetStars();
        }

        function setRating(rating) {
            document.getElementById('rating').value = rating;
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

        function resetStars() {
            document.querySelectorAll('.star-button').forEach(s => {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            });
        }

        // Submit Review
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = "Submitting...";

            try {
                const res = await fetch('/reviews', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    closeReviewModal();
                    Swal.fire("Thank You!", "Your review has been submitted.", "success").then(() => location
                        .reload());
                } else {
                    throw new Error(data.error || 'Failed');
                }
            } catch (err) {
                Swal.fire("Error", err.message, "error");
            } finally {
                btn.disabled = false;
                btn.innerText = "Submit";
            }
        });

        // Search Filter
        document.getElementById('request-search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.sr-status-tab-content:not(.hidden) .sr-request-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? '' : 'none';
            });
        });

        const searchInput = document.getElementById('request-search');
const categoryFilter = document.getElementById('category-filter');

function filterItems() {
    const query = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value; // Don't lowerCase this yet, match exact value

    // 1. Identify which tab is currently visible
    const activeTabContent = document.querySelector('.sr-status-tab-content:not(.hidden)');
    
    if (!activeTabContent) return;

    // 2. Select items only within the active tab
    const items = activeTabContent.querySelectorAll('.sr-request-item');

    items.forEach(item => {
        // Get text content for search
        const text = item.textContent.toLowerCase();
        // Get category data attribute
        const itemCategory = item.getAttribute('data-category');

        // Check Matches
        const matchesSearch = text.includes(query);
        const matchesCategory = selectedCategory === "" || itemCategory === selectedCategory;

        // Toggle Visibility
        if (matchesSearch && matchesCategory) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Attach Event Listeners
searchInput.addEventListener('input', filterItems);
categoryFilter.addEventListener('change', filterItems);

// Hook into tab switching to re-apply filters when changing tabs
const originalShowStatusTab = window.showStatusTab; // Save old function if defined strictly globally
window.showStatusTab = function(status) {
    // Run original tab switch logic (from previous code)
    document.querySelectorAll('.sr-status-tab-content').forEach(content => content.classList.add('hidden'));
    document.querySelectorAll('.sr-status-tab-button').forEach(button => {
        button.classList.remove('bg-indigo-50', 'text-indigo-700', 'shadow-sm');
        button.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
    });
    document.getElementById(status + '-content').classList.remove('hidden');
    const activeTab = document.getElementById(status + '-tab');
    activeTab.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
    activeTab.classList.add('bg-indigo-50', 'text-indigo-700', 'shadow-sm');

    // Re-run filter immediately so the new tab respects current search/category inputs
    filterItems();
};
    </script>
</x-app-layout>
