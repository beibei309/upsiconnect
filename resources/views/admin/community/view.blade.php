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
        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('uploads/profile/default.png') }}"
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

    <!-- VERIFICATION DOCUMENTS (New Section) -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Verification Documents</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Live Selfie -->
            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase mb-2">Live Selfie Check</h3>
                @if($user->selfie_media_path)
                    <div class="border rounded-lg p-2 inline-block bg-gray-50">
                        <img src="{{ route('admin.verifications.selfie', $user->id) }}" 
                             class="h-64 rounded object-cover mb-2 border" 
                             alt="Live Selfie">
                        
                        @if($user->verification_note)
                            <div class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded text-center border border-yellow-200">
                                Challenge: {{ $user->verification_note }}
                            </div>
                        @else
                            <div class="text-gray-500 text-xs text-center">No challenge note recorded</div>
                        @endif
                        
                        <button onclick="openSelfieModal({{ $user->id }})" class="block text-center text-xs text-blue-600 hover:underline mt-2">
                            View Full Size
                        </button>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-red-600 bg-red-50 p-4 rounded border border-red-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-sm font-medium">No selfie uploaded</span>
                    </div>
                @endif
            </div>

            <!-- Proof Document -->
            <div class="bg-white shadow rounded-lg p-6 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">User Last Known Location</h2>
        @if($user->latitude && $user->longitude)
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Active Tracking</span>
        @else
            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">No Location Data</span>
        @endif
    </div>

    @if($user->latitude && $user->longitude)
        <div id="map" class="w-full h-80 rounded-lg border shadow-sm z-0"></div>
        
        <div class="mt-3 flex gap-4 text-sm text-gray-600">
            <p><strong>Lat:</strong> {{ $user->latitude }}</p>
            <p><strong>Long:</strong> {{ $user->longitude }}</p>
            <a href="https://www.google.com/maps/search/?api=1&query={{ $user->latitude }},{{ $user->longitude }}" 
               target="_blank" 
               class="text-blue-600 hover:underline ml-auto">
                Open in Google Maps →
            </a>
        </div>
    @else
        <div class="bg-gray-50 border-2 border-dashed rounded-lg p-10 text-center">
            <p class="text-gray-500">Location coordinates not available for this user.</p>
        </div>
    @endif
</div>
            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase mb-2">Proof Document</h3>
                @if($user->verification_document_path)
                    <div class="border rounded-lg p-4 bg-blue-50 border-blue-100">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900">Document Submitted</p>
                                <p class="text-xs text-blue-700">Protected File (Local Storage)</p>
                            </div>
                        </div>
                        
                        <button onclick="openDocumentModal({{ $user->id }})" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition shadow-sm w-full justify-center">
                            Open Document
                        </button>
                    </div>
                @else
                     <div class="flex items-center gap-2 text-red-600 bg-red-50 p-4 rounded border border-red-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-sm font-medium">No document uploaded</span>
                    </div>
                @endif
            </div>
        </div>
    </div>   

    <!-- ACCOUNT INFO -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-xl font-semibold mb-3">Account Information</h2>

        <p class="text-gray-700"><strong>User ID:</strong> {{ $user->id }}</p>
        <p class="text-gray-700"><strong>Registered On:</strong> {{ $user->created_at->format('d M Y, h:i A') }}</p>
        <p class="text-gray-700"><strong>Last Updated:</strong> {{ $user->updated_at->format('d M Y, h:i A') }}</p>

    </div>

</div>

<!-- Selfie Modal -->
<div id="selfieModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeSelfieModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeSelfieModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold">&times;</button>
        <img id="selfieImage" src="" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl" alt="Selfie">
    </div>
</div>

<!-- Document Modal -->
<div id="documentModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeDocumentModal()">
    <div class="relative max-w-6xl max-h-full w-full" onclick="event.stopPropagation()">
        <button onclick="closeDocumentModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold">&times;</button>
        <iframe id="documentFrame" src="" class="w-full h-[90vh] bg-white rounded-lg shadow-2xl"></iframe>
    </div>
</div>

<script>
function openSelfieModal(userId) {
    document.getElementById('selfieImage').src = `/admin/verifications/${userId}/selfie`;
    document.getElementById('selfieModal').classList.remove('hidden');
}

function closeSelfieModal() {
    document.getElementById('selfieModal').classList.add('hidden');
}

function openDocumentModal(userId) {
    document.getElementById('documentFrame').src = `/admin/verifications/${userId}/document`;
    document.getElementById('documentModal').classList.remove('hidden');
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Pastikan data lat/long wujud sebelum run script
    const lat = {{ $user->latitude ?? 'null' }};
    const lng = {{ $user->longitude ?? 'null' }};

    if (lat && lng) {
        // Initialize map
        const map = L.map('map').setView([lat, lng], 15);

        // Add OpenStreetMap layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add Marker
        const userIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // Icon marker
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38]
        });

        L.marker([lat, lng], {icon: userIcon})
            .addTo(map)
            .bindPopup("<b>{{ $user->name }}</b><br>Last seen here.")
            .openPopup();
    }
});
</script>

@endsection
