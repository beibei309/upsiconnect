@extends('admin.layout')

@section('content')
<div class="px-6 py-8 bg-gray-50 min-h-screen">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Service Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track all service appointments.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.requests.export', array_merge(request()->all(), ['format' => 'csv'])) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export CSV
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <form method="GET" action="{{ route('admin.requests.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm" 
                       placeholder="Search by Requester, Seller, or Service title...">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm text-sm">
                Filter Results
            </button>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="px-6 py-4">Request Details</th>
                        <th class="px-6 py-4">Parties Involved</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Range Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        <td class="px-6 py-4 align-top">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm mb-1">{{ $request->studentService->title ?? 'Service Deleted' }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 capitalize">
                                        {{ str_replace('"', '', $request->selected_package) }} Package
                                    </span>
                                    <span class="text-xs text-gray-400" title="{{ $request->created_at }}">
                                        Requested {{ $request->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if($request->message)
                                    <div class="mt-2 text-xs text-gray-500 bg-gray-100 p-2 rounded border border-gray-200 italic max-w-xs">
                                        "{{ Str::limit($request->message, 50) }}"
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <div class="flex flex-col gap-3">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold">Requester</p>
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                            {{ substr($request->requester->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-700 font-medium">{{ $request->requester->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold">Seller</p>
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 text-xs font-bold">
                                            {{ substr($request->provider->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-700 font-medium">{{ $request->provider->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 pyh-4 align-top whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="flex items-center text-sm text-gray-700 font-medium mb-1">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($request->selected_dates)->format('d M, Y') }}
                                </div>
                                @if($request->start_time && $request->end_time)
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($request->start_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($request->end_time)->format('h:i A') }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Time not specified</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">
                                RM {{ number_format($request->offered_price, 2) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 align-top">
                            @php
                                $statusClasses = match($request->status) {
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'accepted', 'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'cancelled', 'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 align-top text-right">
                            <form action="{{ route('admin.requests.destroy', $request->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors duration-200 p-2 rounded-full hover:bg-red-50" title="Delete Request">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-base font-medium">No requests found</p>
                                <p class="text-sm mt-1">Try adjusting your search filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection