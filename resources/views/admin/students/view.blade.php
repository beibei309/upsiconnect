@extends('admin.layout')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Back Button -->
    <a href="{{ route('admin.students.index') }}" 
       class="text-blue-600 hover:underline text-sm mb-4 inline-block">
        ← Back to Students
    </a>

    <!-- Profile Header -->
    <div class="bg-white shadow rounded-lg p-6 flex gap-6 items-center">
        
        <!-- Profile Photo -->
        <img src="{{ asset($student->profile_photo_path ?? 'uploads/profile/default.png') }}"
             class="w-32 h-32 rounded-full object-cover border" />

        <div class="flex-1">

            <h1 class="text-3xl font-bold">{{ $student->name }}</h1>

            <!-- Email & Phone -->
            <p class="text-gray-600">{{ $student->email }}</p>
            <p class="text-gray-600">{{ $student->phone ?? 'No phone provided' }}</p>

            <!-- Student ID -->
            <p class="text-gray-700 mt-1"><strong>Student ID:</strong> {{ $student->student_id }}</p>

            <!-- Faculty -->
            <p class="text-gray-700"><strong>Faculty:</strong> 
                {{ $student->faculty ?? 'Not provided' }}
            </p>

            <!-- Course -->
            <p class="text-gray-700"><strong>Course:</strong> 
                {{ $student->course ?? 'Not provided' }}
            </p>

            <!-- Verification -->
            <div class="mt-2">
                @if($student->verification_status == 'approved')
                    <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                        Verified
                    </span>
                @else
                    <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                        Not Verified
                    </span>
                @endif
            </div>

            <!-- Ban Status -->
            @if($student->is_suspended)
                <div class="mt-2">
                    <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">
                        Banned
                    </span>

                    <p class="text-sm text-red-700 mt-1">
                        <strong>Reason:</strong> {{ $student->blacklist_reason }}
                    </p>
                </div>
            @endif

        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-2">

            <a href="{{ route('admin.students.edit', $student->id) }}"
               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
               Edit Student
            </a>

            @if($student->is_suspended)
                <form action="{{ route('admin.students.unban', $student->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                        Unban Student
                    </button>
                </form>
            @else
                <button onclick="openBanModal({{ $student->id }})"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                    Ban Student
                </button>
            @endif

        </div>

    </div>

    <!-- ABOUT SECTION -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-3">Bio</h2>

        <p class="text-gray-700 leading-relaxed">
            {{ $student->bio ?? 'This student did not provide a bio.' }}
        </p>
    </div>

    <!-- ACCOUNT INFO -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-3">Account Information</h2>

        <p class="text-gray-700"><strong>User ID:</strong> {{ $student->id }}</p>
        <p class="text-gray-700"><strong>Registered On:</strong> {{ $student->created_at->format('d M Y, h:i A') }}</p>
        <p class="text-gray-700"><strong>Last Updated:</strong> {{ $student->updated_at->format('d M Y, h:i A') }}</p>

    </div>
</div>

@endsection
