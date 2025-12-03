@extends('admin.layout')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-3xl font-bold mb-6">Manage Services</h1>

    <!-- Search Form -->
    <form class="mb-6" method="GET" action="{{ route('admin.services.index') }}">
        <div class="flex space-x-4">
            <input type="text" name="search" placeholder="Search services..."
                   class="p-2 border rounded w-1/3" value="{{ request('search') }}">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">Search</button>
        </div>
    </form>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <!-- Services Table -->
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr class="border-b">
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Student Name</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Title</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Category</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Image</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Description</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Price Range</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Status</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Approval Status</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">{{ $service->user_id ? $service->student->name : 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">{{ $service->title }}</td>
                        <td class="py-3 px-4 text-sm" style="color:{{ $service->category ? $service->category->color : 'N/A' }}">{{ $service->category ? $service->category->name : 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">
                            @if($service->image_path)
                                <img src="{{ asset('storage/' . $service->image_path) }}" class="w-16 h-16 object-cover rounded" alt="{{ $service->title }}">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm">{{ Str::limit($service->description, 50) }}</td>
                        <td class="py-3 px-4 text-sm">{{ $service->suggested_price ? 'RM ' . number_format($service->suggested_price, 2) : 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">{{ $service->status }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                @if($service->approval_status === 'pending') bg-yellow-100 text-yellow-800 
                                @elseif($service->approval_status === 'approved') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($service->approval_status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            @if($service->approval_status === 'pending')
                                <div class="flex space-x-2">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.services.approve', $service->id) }}" method="POST" class="inline-block approval-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="text-green-600 hover:underline" data-action="approve">Approve</button>
                                    </form>
                                    <!-- Reject Button -->
                                    <form action="{{ route('admin.services.reject', $service->id) }}" method="POST" class="inline-block approval-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="text-red-600 hover:underline" data-action="reject">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                     
@endsection
