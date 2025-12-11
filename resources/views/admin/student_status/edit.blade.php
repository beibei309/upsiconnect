@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Edit Student Status</h1>

<form action="{{ route('admin.student_status.update', $status->id) }}"
      method="POST" class="bg-white p-6 rounded shadow">

    @csrf
    @method('PUT')

    {{-- STUDENT (READ-ONLY) --}}
    <div class="mb-4">
        <label class="font-semibold block mb-1">Student</label>

        <input type="text" 
               value="{{ $status->student->name }} ({{ $status->student->student_id }})"
               class="border p-2 rounded w-full bg-gray-100 text-gray-600" disabled>

        {{-- keep student_id in hidden field --}}
        <input type="hidden" name="student_id" value="{{ $status->student_id }}">
    </div>

    {{-- SEMESTER --}}
    <div class="mb-4">
        <label class="font-semibold block mb-1">Semester</label>
        <input type="text" name="semester" value="{{ $status->semester }}"
               class="border p-2 rounded w-full" required>
    </div>

    {{-- STATUS --}}
    <div class="mb-4">
        <label class="font-semibold block mb-1">Status</label>
        <select name="status" class="border p-2 rounded w-full">
            <option value="Active"   {{ $status->status == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ $status->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="Deferred" {{ $status->status == 'Deferred' ? 'selected' : '' }}>Deferred</option>
        </select>
    </div>

    <p class="text-gray-600 text-sm mb-3">
        * Effective date will update automatically to today when record updated.
    </p>

    <div class="flex gap-3">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>

        <a href="{{ route('admin.student_status.index') }}"
           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
            Cancel
        </a>
    </div>

</form>

@endsection
