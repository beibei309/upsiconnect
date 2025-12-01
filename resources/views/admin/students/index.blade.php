@extends('admin.layout')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-4">Manage Students</h1>

    <!-- Search -->
    <form class="mb-4">
        <input type="text" name="search" placeholder="Search student..."
               class="p-2 border rounded w-1/3" value="{{ request('search') }}">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
    </form>

    <div class="bg-white shadow rounded-lg p-6">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Name</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Phone</th>
                    <th class="py-3">Student ID</th>
                    <th class="py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr class="border-b">
                        <td class="py-3">{{ $student->name }}</td>
                        <td class="py-3">{{ $student->email }}</td>
                        <td class="py-3">{{ $student->phone }}</td>
                        <td class="py-3">{{ $student->student_id }}</td>
                        <td class="py-3">
                            <a href="{{ route('admin.students.edit', $student->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>

                               <!-- Ban / Unban -->
            @if($student->is_suspended)
                <form action="{{ route('admin.students.unban', $student->id) }}" method="POST">
                    @csrf
                    <button class="text-green-600 hover:underline">Unban</button>
                </form>
            @else
                <form action="{{ route('admin.students.ban', $student->id) }}" method="POST">
                    @csrf
                    <button class="text-yellow-600 hover:underline">Ban</button>
                </form>
            @endif

            <!-- Delete -->
            <form action="{{ route('admin.students.delete', $student->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this student?');">
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
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
