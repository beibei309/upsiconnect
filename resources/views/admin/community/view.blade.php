@extends('admin.layout')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Back Button -->
    <a href="{{ route('admin.community.index') }}" class="text-blue-600 hover:underline text-sm mb-4 inline-block">
        ← Back to Community List
    </a>

    <!-- Profile Header -->
    <div class="bg-white shadow rounded-lg p-6 flex gap-6 items-center">
        
        <!-- Profile Photo -->
        <img src="{{ asset($user->profile_photo_path ?? 'uploads/profile/default.png') }}"
             class="w-32 h-32 rounded-full object-cover border" />

        <div class="flex-1">

            <!-- Name -->
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>

            <!-- Email + Phone -->
            <p class="text-gray-600">{{ $user->email }}</p>
            <p class="text-gray-600">{{ $user->phone ?? 'No phone provided' }}</p>

            <!-- Verification -->
            <div class="mt-2">
                @if($user->verification_status == 'approved')
                    <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                        Approved
                    </span>
                @elseif($user->verification_status == 'pending')
                    <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                        Pending
                    </span>
                @else
                    <span class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full">
                        Rejected
                    </span>
                @endif
            </div>

            <!-- Blacklist -->
            @if($user->is_blacklisted)
                <div class="mt-2">
                    <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">
                        Blacklisted
                    </span>

                    <p class="text-sm text-red-700 mt-1">
                        Reason: {{ $user->blacklist_reason }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-2">

            <!-- Edit -->
            <a href="{{ route('admin.community.edit', $user->id) }}"
               class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
               Edit User
            </a>

            <!-- Blacklist / Unblacklist -->
            @if($user->is_blacklisted)
                <form action="{{ route('admin.community.unblacklist', $user->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                        Remove Blacklist
                    </button>
                </form>
            @else
                <form action="{{ route('admin.community.blacklist', $user->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                        Blacklist User
                    </button>
                </form>
            @endif

            <!-- Delete -->
            <form action="{{ route('admin.community.delete', $user->id) }}" 
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-black text-sm">
                    Delete User
                </button>
            </form>

        </div>

    </div>

    <!-- BIO SECTION -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-3">About</h2>

        <p class="text-gray-700 leading-relaxed">
            {{ $user->bio ?? 'This user did not provide a bio.' }}
        </p>
    </div>

    <!-- ACCOUNT INFO -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-3">Account Information</h2>

        <p class="text-gray-700"><strong>User ID:</strong> {{ $user->id }}</p>
        <p class="text-gray-700"><strong>Registered On:</strong> {{ $user->created_at->format('d M Y, h:i A') }}</p>
        <p class="text-gray-700"><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y, h:i A') }}</p>

    </div>

</div>

@endsection
