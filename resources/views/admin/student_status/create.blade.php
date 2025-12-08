@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Add Student Status</h1>

<form action="{{ route('admin.student_status.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf

    <div class="mb-4">
        <label class="font-semibold block mb-1">Student</label>
        <select name="student_id" class="border p-2 rounded w-full" required>
            <option value="">Select Student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="font-semibold block mb-1">Semester</label>
        <input type="text" name="semester" class="border p-2 rounded w-full"
               placeholder="Semester 1 2025" required>
    </div>

    <div class="mb-4">
        <label class="font-semibold block mb-1">Status</label>
        <select name="status" class="border p-2 rounded w-full" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Deferred">Deferred</option>
        </select>
    </div>

    <p class="text-gray-600 mb-3 text-sm">
        * Effective date will be automatically set to today.
    </p>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>

    <!-- CANCEL (BACK TO LIST PAGE) -->
        <a href="{{ route('admin.student_status.index') }}"
           class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
            Cancel
        </a>
    </div>

</form>

@endsection
