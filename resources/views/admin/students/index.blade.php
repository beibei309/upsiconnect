@extends('admin.layout')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-4">Manage Students</h1>

    <!-- Search + Filters Row -->
    <div class="flex items-center gap-3 mb-4">

        <!-- Search -->
        <form method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" placeholder="Search student..."
                class="p-2 border rounded w-1/3"
                value="{{ request('search') }}">

            <button class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
        </form>

    </div>

    <!-- Filter Pills -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.students.index') }}"
        class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            All
        </a>

        <a href="{{ route('admin.students.index', ['status' => 'active', 'search' => request('search')]) }}"
        class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Active
        </a>

        <a href="{{ route('admin.students.index', ['status' => 'banned', 'search' => request('search')]) }}"
        class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == 'banned' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Banned
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Phone</th>
                    <th class="py-3 px-4">Student ID</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $student)
                <tr class="border-b">

                    <!-- NAME -->
                    <td class="py-3 px-4 text-sm">{{ $student->name }}</td>

                    <!-- EMAIL -->
                    <td class="py-3 px-4 text-sm">{{ $student->email }}</td>

                    <!-- PHONE -->
                    <td class="py-3 px-4 text-sm">{{ $student->phone }}</td>

                    <!-- STUDENT ID -->
                    <td class="py-3 px-4 text-sm">{{ $student->student_id }}</td>

                    <!-- STATUS COLUMN -->
                    <td class="py-3 px-4">

                        @if($student->is_suspended)
                        <div>
                            <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">
                                Banned
                            </span>

                            @if($student->blacklist_reason)
                            <p class="text-xs text-red-700 mt-1">{{ $student->blacklist_reason }}</p>
                            @endif
                        </div>

                        @else
                            @if($student->verification_status == 'approved')
                                <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                                    Verified
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                                    Not Verified
                                </span>
                            @endif
                        @endif

                    </td>

                    <!-- ACTIONS -->
                    <td class="py-3 px-4 flex gap-3">

                        <!-- VIEW -->
                        <a href="{{ route('admin.students.view', $student->id) }}"
                            class="text-blue-600 hover:underline">
                            View
                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('admin.students.edit', $student->id) }}"
                            class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <!-- BAN / UNBAN -->
                        @if($student->is_suspended)

                            <!-- UNBAN BUTTON -->
                            <form action="{{ route('admin.students.unban', $student->id) }}" method="POST">
                                @csrf
                                <button class="text-green-600 hover:underline">
                                    Unban
                                </button>
                            </form>

                        @else
                            <!-- BAN BUTTON (Opens Modal) -->
                            <button onclick="openBanModal({{ $student->id }})"
                                class="text-yellow-600 hover:underline">
                                Ban
                            </button>
                        @endif

                        <!-- DELETE -->
                        <form action="{{ route('admin.students.delete', $student->id) }}"
                            method="POST"
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

<!-- Ban Reason Modal -->
<div id="banModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-xl">

        <h2 class="text-xl font-bold mb-4">Ban Student</h2>

        <p class="text-gray-600 mb-3">Please provide a reason for banning this student:</p>

        <textarea id="banReason" rows="3"
            class="w-full border rounded p-2 focus:ring focus:ring-red-300"
            placeholder="Write reason..."></textarea>

        <!-- FORM FOR BAN -->
        <form id="banForm" method="POST" class="hidden">
            @csrf
        </form>

        <div class="mt-5 flex justify-end gap-3">
            <button onclick="closeBanModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                Cancel
            </button>

            <button onclick="submitBan()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                Confirm Ban
            </button>
        </div>

    </div>
</div>

<script>
let selectedStudentId = null;

function openBanModal(id) {
    selectedStudentId = id;
    document.getElementById("banModal").classList.remove("hidden");
}

function closeBanModal() {
    document.getElementById("banModal").classList.add("hidden");
    document.getElementById("banReason").value = "";
}

function submitBan() {
    const reason = document.getElementById("banReason").value.trim();

    if (!reason) {
        alert("Please enter a ban reason.");
        return;
    }

    const form = document.getElementById("banForm");
    form.action = "/admin/students/" + selectedStudentId + "/ban";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "blacklist_reason";
    input.value = reason;

    form.appendChild(input);
    form.submit();
}
</script>

@endsection
