@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Student Status List</h1>

<a href="{{ route('admin.student_status.create') }}"
   class="px-4 py-2 bg-blue-600 text-white rounded mb-4 inline-block">
    + Add Status
</a>

@if(session('success'))
    <div class="p-3 bg-green-100 text-green-800 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg p-6">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-3 px-4">Student</th>
                <th class="py-3 px-4">Matric No</th>
                <th class="py-3 px-4">Semester</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Effective Date</th>
                <th class="py-3 px-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($statuses as $status)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $status->student->name }}</td>
                    <td class="py-3 px-4">{{ $status->matric_no }}</td>
                    <td class="py-3 px-4">{{ $status->semester }}</td>
                    <td class="py-3 px-4">{{ $status->status }}</td>
                    <td class="py-3 px-4">{{ $status->effective_date }}</td>

                    <td class="py-3 px-4 flex gap-3">
                        <a href="{{ route('admin.student_status.edit', $status->id) }}"
                           class="text-blue-600 hover:underline">Edit</a>

                        <form method="POST"
                              action="{{ route('admin.student_status.delete', $status->id) }}"
                              onsubmit="return confirm('Delete this record?')">
                              @csrf
                              @method('DELETE')
                              <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $statuses->links() }}
    </div>
</div>

@endsection
