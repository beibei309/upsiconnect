@extends('admin.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Header & Search --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Service Requests</h1>
                <p class="text-sm text-gray-500 mt-1">Review and manage all student-to-community transactions.</p>
            </div>

            <form method="GET" action="{{ route('admin.requests.index') }}" class="w-full md:w-80">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search student or service..."
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    <button class="absolute right-3 top-2.5 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Requester</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Service & Package</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Provider</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Schedule</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Message</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                {{-- Requester --}}
                                <td class="py-4 px-6">
                                    <div class="text-sm font-semibold text-gray-900">{{ $request->requester->name }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ $request->requester_id }}</div>
                                </td>

                                {{-- Service --}}
                                <td class="py-4 px-6">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->studentService->title }}</div>
                                    <div class="text-xs text-blue-600 font-medium capitalize">{{ $request->selected_package }} Package</div>
                                </td>

                                {{-- Provider --}}
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ $request->provider->name }}
                                </td>

                                {{-- Schedule --}}
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($request->selected_dates)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($request->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($request->end_time)->format('h:i A') }}
                                    </div>
                                </td>

                                {{-- Message --}}
                                <td class="py-4 px-6">
                                    <span class="text-sm font-bold text-gray-900"> {{ $request->message }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                        {{ $request->formatted_status }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="py-4 px-6 text-right">
                                    <form action="{{ route('admin.requests.destroy', $request->id) }}" method="POST" 
                                          onsubmit="return confirm('Permanently delete this request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($requests->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection