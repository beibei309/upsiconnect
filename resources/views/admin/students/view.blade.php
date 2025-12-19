@extends('admin.layout')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- BACK --}}
    <a href="{{ route('admin.students.index') }}" 
       class="text-blue-600 hover:text-blue-800 text-sm mb-4 inline-flex items-center gap-1">
        ← Back to Students
    </a>

    {{-- BASIC INFO --}}
    <div class="bg-white shadow-sm rounded-lg p-6 flex flex-col md:flex-row gap-6 border border-gray-200">

        <div class="shrink-0">
            @php
            use Illuminate\Support\Facades\Storage;
            @endphp
            
            <img
            src="{{ $student->profile_photo_path
            ? Storage::url($student->profile_photo_path)
            : asset('uploads/profile/default.png') }}"
            alt="{{ $student->name }}"
            class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm"
            />
        </div>

        <div class="flex-1">

            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">{{ $student->name }}</h1>
                    <p class="text-gray-500">{{ $student->email }}</p>
                </div>

                {{-- VERIFICATION --}}
                @if($student->verification_status === 'approved')
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        Verified
                    </span>
                @else
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                        Pending
                    </span>
                @endif
            </div>

            {{-- DETAILS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm">

                <div class="space-y-2">
                    <p><strong>Student ID:</strong> {{ $student->student_id ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $student->phone ?? '-' }}</p>
                    <p>
                        <strong>Role:</strong>
                        @if($student->role === 'helper')
                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-bold">
                                Helper
                            </span>
                        @else
                            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-bold">
                                Student
                            </span>
                        @endif
                    </p>
                </div>

                <div class="space-y-2">
                    <p><strong>Faculty:</strong> {{ $student->faculty ?? '-' }}</p>
                    <p><strong>Course:</strong> {{ $student->course ?? '-' }}</p>
                    <p>
                        <strong>Graduation:</strong>
                        @if($student->studentStatus && $student->studentStatus->graduation_date)
                            {{ \Carbon\Carbon::parse($student->studentStatus->graduation_date)->format('d M Y') }}
                        @else
                            <span class="text-gray-400 italic">Not set</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- BANNED --}}
            @if($student->is_suspended)
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded">
                    <strong class="text-red-700">Student Banned</strong>
                    <p class="text-sm text-red-600 mt-1">
                        Reason: {{ $student->blacklist_reason }}
                    </p>
                </div>
            @endif
        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.students.edit', $student->id) }}"
               class="px-4 py-2 border rounded text-sm text-center">
                Edit Profile
            </a>

            @if($student->is_suspended)
                <form action="{{ route('admin.students.unban', $student->id) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded text-sm w-full">
                        Unban
                    </button>
                </form>
            @else
                <button onclick="openBanModal({{ $student->id }})"
                        class="px-4 py-2 bg-red-600 text-white rounded text-sm">
                    Ban
                </button>
            @endif
        </div>
    </div>

    {{-- HELPER VERIFICATION INFO --}}
    @if($student->role === 'helper')
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 shadow-sm rounded-lg p-6 mt-6 border-2 border-green-200">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <h2 class="text-xl font-bold text-green-800">Helper Verification Information</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Verification Date -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Verified Since</p>
                @if($student->helper_verified_at)
                    <p class="text-lg font-bold text-gray-900">
                        {{ $student->helper_verified_at->format('F d, Y') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $student->helper_verified_at->format('h:i A') }}
                    </p>
                @else
                    <p class="text-gray-400 italic">Not recorded</p>
                @endif
            </div>

            <!-- GPS Location -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">📍 Location</p>
                @if($student->latitude && $student->longitude)
                    <p class="text-sm font-mono text-gray-900">
                        {{ number_format($student->latitude, 6) }}, {{ number_format($student->longitude, 6) }}
                    </p>
                    @if($student->address)
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $student->address }}
                        </p>
                    @endif
                    @if($student->location_verified_at)
                        <p class="text-xs text-gray-400 mt-1">
                            Verified: {{ $student->location_verified_at->format('M d, Y') }}
                        </p>
                    @endif
                @else
                    <p class="text-gray-400 italic">No location data</p>
                @endif
            </div>

            <!-- Verification Selfie -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">📸 Verification Selfie</p>
                @if($student->selfie_media_path)
                    <button onclick="openHelperSelfieModal({{ $student->id }})" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Selfie
                    </button>
                @else
                    <p class="text-gray-400 italic">No selfie uploaded</p>
                @endif
            </div>

            <!-- Revoke Helper Status -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">⚙️ Actions</p>
                <form action="{{ route('admin.students.revoke_helper', $student->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to revoke helper status? This will convert the user back to a regular student.')">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Revoke Helper Status
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- HELPER PROFILE --}}
    @if($student->role === 'helper')
    <div class="bg-white shadow-sm rounded-lg p-6 mt-6 border border-gray-200">
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-xl font-semibold">Helper Profile</h2>
            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded">
                Helper
            </span>
        </div>

        @if($student->work_experience_message)
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Experience / Description
        </h3>

        <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
            {{ $student->work_experience_message }}
        </div>
    </div>
@endif

        @if($student->skills)
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Skills
        </h3>

        <ul class="list-disc list-inside space-y-1 text-gray-700 text-sm">
            @foreach(explode(',', $student->skills) as $skill)
                <li>{{ trim($skill) }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- RESUME / CV --}}
@if($student->work_experience_file)
    <div class="mt-4">
        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">
            Resume / CV
        </h3>

        <a href="{{ asset('storage/' . $student->work_experience_file) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50
                  border border-blue-200 text-blue-700 rounded-lg
                  hover:bg-blue-100 transition text-sm font-medium">

            View Resume (PDF)
        </a>
    </div>
@endif

    </div>
    @endif

    {{-- ABOUT --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mt-6 border border-gray-200">
        <h2 class="text-xl font-semibold mb-3">About</h2>
        @if($student->bio)
            <p class="text-gray-700 whitespace-pre-line">{{ $student->bio }}</p>
        @else
            <p class="text-gray-400 italic">No bio provided.</p>
        @endif
    </div>

    {{-- IDENTITY VERIFICATION --}}
<div class="bg-white shadow rounded-lg p-6 mt-6 border border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">
        Identity Verification (Live Selfie)
    </h2>

    @if($student->selfie_media_path)
        <p class="text-sm text-gray-600 mb-4">
            Live selfie submitted during verification process.
        </p>

        <img src="{{ route('admin.verifications.selfie', $student->id) }}"
             alt="Live Selfie"
             class="w-40 h-40 rounded-lg object-cover border shadow">

    @else
        <div class="flex items-center gap-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            
            <div class="w-24 h-24 flex items-center justify-center bg-yellow-100 rounded border">
                <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div>
                <p class="font-semibold text-yellow-800">
                    Live Selfie Not Submitted
                </p>
                <p class="text-sm text-yellow-700">
                    This student has not uploaded a live selfie for identity verification.
                </p>
                <p class="text-xs text-yellow-600 mt-1 italic">
                    Required for verification approval.
                </p>
            </div>
        </div>
    @endif
</div>

    {{-- SYSTEM INFO --}}
    <div class="bg-white shadow-sm rounded-lg p-6 mt-6 border border-gray-200">
        <h2 class="text-sm font-bold text-gray-500 uppercase mb-4">System Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div><strong>ID:</strong> #{{ $student->id }}</div>
            <div><strong>Registered:</strong> {{ $student->created_at->format('d M Y') }}</div>
            <div><strong>Updated:</strong> {{ $student->updated_at->format('d M Y') }}</div>
        </div>
    </div>
</div>

{{-- BAN MODAL --}}
<div id="banModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-bold mb-3">Ban Student</h2>

        <textarea id="banReason" rows="3" class="w-full border rounded p-2 mb-4"
                  placeholder="Reason for ban..."></textarea>

        <form id="banForm" method="POST">@csrf</form>

        <div class="flex justify-end gap-2">
            <button onclick="closeBanModal()" class="px-4 py-2 border rounded">Cancel</button>
            <button onclick="submitBan()" class="px-4 py-2 bg-red-600 text-white rounded">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
let selectedStudentId = null;

function openBanModal(id) {
    selectedStudentId = id;
    document.getElementById('banModal').classList.remove('hidden');
}

function closeBanModal() {
    document.getElementById('banModal').classList.add('hidden');
    document.getElementById('banReason').value = '';
}

function submitBan() {
    const reason = document.getElementById('banReason').value.trim();
    if (!reason) return alert('Please enter a reason.');

    const form = document.getElementById('banForm');
    form.action = `/admin/students/${selectedStudentId}/ban`;

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'blacklist_reason';
    input.value = reason;
    form.appendChild(input);

    form.submit();
}

// Helper Selfie Modal
function openHelperSelfieModal(studentId) {
    const modal = document.createElement('div');
    modal.id = 'helperSelfieModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    modal.onclick = () => modal.remove();
    
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
            <button onclick="this.closest('#helperSelfieModal').remove()" 
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold">
                ×
            </button>
            <img src="/admin/students/${studentId}/selfie" 
                 class="max-w-full max-h-[90vh] rounded-lg shadow-2xl" 
                 alt="Helper Verification Selfie">
        </div>
    `;
    
    document.body.appendChild(modal);
}

</script>

@endsection
