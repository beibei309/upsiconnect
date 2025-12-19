@extends('admin.layout')

@section('content')
<div class="px-6 py-4">
    <h1 class="text-3xl font-bold mb-6">Manage Service Requests</h1>

    <form class="mb-6" method="GET" action="{{ route('admin.requests.index') }}">
        <div class="flex space-x-4">
            <input type="text" name="search" placeholder="Search by student or service..."
                   class="p-2 border rounded w-1/3" value="{{ request('search') }}">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">Search</button>
        </div>
    </form>
    <div class="mb-6 flex gap-2 items-center">
    <a href="{{ route('admin.requests.export', array_merge(request()->all(), ['format' => 'csv'])) }}"
       class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
        Export CSV
    </a>
</div>


    <div class="bg-white shadow-lg rounded-lg p-6">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr class="border-b">
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Requester (Community)</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Service Requested</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Provider (Student)</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Request Date</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Price</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Status</th>
                    <th class="py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- 4. Tukar Loop kepada $requests --}}
                @foreach($requests as $request)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm font-bold">{{ $request->requester->name }}</td>
                        
                        <td class="py-3 px-4 text-sm">{{ $request->studentService->title }}</td>
                        
                        <td class="py-3 px-4 text-sm text-gray-500">{{ $request->provider->name }}</td>
                        
                        <td class="py-3 px-4 text-sm">{{ $request->created_at->format('d/m/Y') }}</td>
                        
                        <td class="py-3 px-4 text-sm">RM {{ number_format($request->studentService->suggested_price, 2) }}</td>
                        
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $request->status_color }}"> {{ $request->formatted_status }} </span> 
                        </td>
                        
                        <td class="py-3 px-4 text-sm">
    			    <form action="{{ route('admin.requests.destroy', $request->id) }}" method="POST"            
                                onsubmit="return confirm('Are you sure you want to delete this service permanently?');">
                                @csrf
                                @method('DELETE')
        
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5   
                                                    4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            				       </svg>
        				</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection