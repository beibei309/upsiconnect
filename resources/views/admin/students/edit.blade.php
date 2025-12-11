@extends('admin.layout')

@section('content')

{{-- Success Toast --}}
@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-100 border-l-4 border-green-600 text-green-700 rounded animate-fade-in">
    {{ session('success') }}
</div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
<div class="mb-4 px-4 py-3 bg-red-100 border-l-4 border-red-600 text-red-700 rounded">
    <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="max-w-3xl bg-white shadow-lg rounded-xl p-8 border border-gray-200">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">Edit Student</h1>

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Name</label>
            <input type="text" name="name" value="{{ old('name', $student->name) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Email --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Email</label>
            <input type="email" name="email" value="{{ old('email', $student->email) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Phone --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Student ID --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Student ID</label>
            <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Faculty --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Faculty</label>
            <input type="text" name="faculty" value="{{ old('faculty', $student->faculty) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Course --}}
        <div class="mb-5">
            <label class="font-semibold text-gray-700 mb-1 block">Course</label>
            <input type="text" name="course" value="{{ old('course', $student->course) }}"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Verification Status --}}
        <div class="mb-8">
            <label class="font-semibold text-gray-700 mb-1 block">Verification Status</label>
            <select name="verification_status"
                class="w-full p-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="pending"  {{ $student->verification_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $student->verification_status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $student->verification_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        {{-- Account Status Toggle --}}
        <div class="mt-8">
            <label class="font-semibold text-gray-700 block mb-3">Account Status</label>

            <div class="flex items-center gap-4">

                <span class="text-gray-700 font-medium">
                    {{ $student->is_suspended ? 'Suspended' : 'Active' }}
                </span>

                <form action="{{ $student->is_suspended 
                                ? route('admin.students.unban', $student->id)
                                : route('admin.students.ban', $student->id) }}"
                      method="POST">
                    @csrf

                    {{-- Status Display --}}
<div class="mb-5">
    <label class="font-semibold text-gray-700 mb-1 block">Account Status</label>

    @if($student->is_suspended)
        <span class="px-3 py-1 bg-red-200 text-red-700 rounded-full">Banned</span>
        <p class="text-sm text-red-600 mt-1">{{ $student->blacklist_reason }}</p>
    @else
        <span class="px-3 py-1 bg-green-200 text-green-700 rounded-full">Active</span>
    @endif

    <p class="text-xs text-gray-500 mt-2">
        *Ban / Unban can be managed from the main student list.
    </p>
</div>

                </form>

            </div>
        </div>

        {{-- Buttons --}}
        <div class="mt-12 flex items-center gap-6">
            <button type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                Save Changes
            </button>

            <a href="{{ route('admin.students.index') }}"
                class="text-gray-600 hover:text-gray-900 transition">
                ← Back to List
            </a>
        </div>

    </form>
</div>

@endsection
