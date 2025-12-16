@extends('admin.layout')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-4">Manage Students & Helpers</h1>

    {{-- SEARCH --}}
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
        <form method="GET" class="flex items-center gap-2 flex-grow w-full md:w-auto">
            <input type="text" name="search"
                placeholder="Search by name, email, skills..."
                class="p-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ request('search') }}">

            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <button class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                Search
            </button>
        </form>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.students.index', ['search' => request('search')]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium border transition
           {{ request('status') == '' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
            All Students
        </a>

        <a href="{{ route('admin.students.index', ['status' => 'student', 'search' => request('search')]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium border transition
           {{ request('status') == 'student' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
            Students Only
        </a>

        <a href="{{ route('admin.students.index', ['status' => 'helper', 'search' => request('search')]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium border transition
           {{ request('status') == 'helper' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
            Helpers
        </a>

        <a href="{{ route('admin.students.index', ['status' => 'banned', 'search' => request('search')]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium border transition
           {{ request('status') == 'banned' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
            Banned
        </a>

        <form method="GET" class="flex gap-2">
    {{-- FACULTY FILTER --}}
    <select name="faculty" class="p-2 border rounded-lg text-sm">
        <option value="">All Faculties</option>
        <option value="FKMT" {{ request('faculty')=='FKMT' ? 'selected' : '' }}>FKMT</option>
        <option value="FPE" {{ request('faculty')=='FPE' ? 'selected' : '' }}>FPE</option>
        <option value="FSKIK" {{ request('faculty')=='FSKIK' ? 'selected' : '' }}>FSKIK</option>
        <option value="FSK" {{ request('faculty')=='FSK' ? 'selected' : '' }}>FSK</option>
        <option value="FSMT" {{ request('faculty')=='FSMT' ? 'selected' : '' }}>FSMT</option>
        <option value="FBK" {{ request('faculty')=='FBK' ? 'selected' : '' }}>FBK</option>
        <option value="FPM" {{ request('faculty')=='FPM' ? 'selected' : '' }}>FPM</option>
        <option value="FMUP" {{ request('faculty')=='FMUP' ? 'selected' : '' }}>FMUP</option>
        <option value="FSSKJ" {{ request('faculty')=='FSSKJ' ? 'selected' : '' }}>FSSKJ</option>
        <option value="FTV" {{ request('faculty')=='FTV' ? 'selected' : '' }}>FTV</option>
    </select>


    <button class="px-4 py-2 bg-blue-600 text-white rounded">
        Filter
    </button>
</form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Student Info</th>
                        <th class="py-3 px-4 font-semibold">Role</th>
                        <th class="py-3 px-4 font-semibold">Helper Profile (Skills / Experience)</th>
                        <th class="py-3 px-4 font-semibold">Verification</th>
                        <th class="py-3 px-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- STUDENT INFO --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                
                        {{-- Profile Photo --}}
                        <img src="{{ $student->profile_photo_path
                        ? \Illuminate\Support\Facades\Storage::url($student->profile_photo_path)
                        : asset('uploads/profile/default.png') }}"
                        alt="{{ $student->name }}"
                        class="w-10 h-10 rounded-full object-cover border"
                        >
                        
                        {{-- Student Info --}}
                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $student->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $student->email }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $student->student_id ?? 'No Matric' }} | {{ $student->phone ?? '-' }}
                            </div>
                            
                            @if($student->faculty)
                            <div class="text-xs text-blue-500 font-medium">
                                {{ $student->faculty }}
                            </div>
                            @endif

                        </div>
                    </div>
                </td>



                        {{-- ROLE --}}
                        <td class="py-3 px-4">
                            @if($student->role === 'helper')
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Helper
                            </span>
                            @else
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Student
                            </span>
                            @endif

                        </td>

                        {{-- HELPER PROFILE --}}
                        <td class="py-3 px-4 text-sm max-w-xs">
                            @if(!empty($student->skills))
                                <div class="mb-1">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Skills:</span>
                                    <span class="block text-gray-700 truncate">
                                        {{ Str::limit($student->skills, 40) }}
                                    </span>
                                </div>
                            @endif

                            @if(!empty($student->work_experience_message))
                                <div class="mb-1">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Exp:</span>
                                    <span class="block italic text-gray-600 truncate">
                                        {{ Str::limit($student->work_experience_message, 30) }}
                                    </span>
                                </div>
                            @endif

                            @if(empty($student->skills) && empty($student->work_experience_message))
                                <span class="text-gray-400 text-xs italic">No helper profile data</span>
                            @endif
                        </td>

                        {{-- VERIFICATION / BAN --}}
                        <td class="py-3 px-4">
                            @if($student->is_suspended)
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Banned
                                </span>
                                @if($student->blacklist_reason)
                                    <p class="text-xs text-red-600 mt-1 truncate" title="{{ $student->blacklist_reason }}">
                                        {{ $student->blacklist_reason }}
                                    </p>
                                @endif
                            @else
                                @if($student->verification_status === 'approved')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.students.view', $student->id) }}" class="text-blue-600 text-sm">View</a>
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="text-gray-600 text-sm">Edit</a>

                                @if($student->is_suspended)
                                    <form action="{{ route('admin.students.unban', $student->id) }}" method="POST">
                                        @csrf
                                        <button class="text-green-600 text-sm">Unban</button>
                                    </form>
                                @else
                                    <button onclick="openBanModal({{ $student->id }})"
                                            class="text-red-600 text-sm">Ban</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            No students found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- BAN MODAL --}}
<div id="banModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-3">Ban Student</h2>

        <textarea id="banReason" rows="3"
            class="w-full border rounded p-2 mb-4"
            placeholder="Reason for banning..."></textarea>

        <form id="banForm" method="POST">@csrf</form>

        <div class="flex justify-end gap-3">
            <button onclick="closeBanModal()" class="px-4 py-2 border rounded">Cancel</button>
            <button onclick="submitBan()" class="px-4 py-2 bg-red-600 text-white rounded">Confirm</button>
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
    if (!reason) {
        alert('Please enter a reason.');
        return;
    }

    const form = document.getElementById('banForm');
    form.action = `/admin/students/${selectedStudentId}/ban`;

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'blacklist_reason';
    input.value = reason;

    form.appendChild(input);
    form.submit();
}
</script>

@endsection
