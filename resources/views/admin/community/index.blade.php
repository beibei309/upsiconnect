@extends('admin.layout')

@section('content')

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Total Community Users</h3>
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Approved</h3>
        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Pending</h3>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-gray-500 text-sm">Blacklisted</h3>
        <p class="text-2xl font-bold text-red-600">{{ $stats['blacklisted'] }}</p>
    </div>
</div>


<h1 class="text-3xl font-bold mb-6">Manage Community Users</h1>

<!-- SEARCH BAR -->
<form method="GET" class="mb-6 flex gap-3">
    <input type="text" name="search" placeholder="Search community users..."
           class="p-2 border rounded w-1/3" value="{{ request('search') }}" />
    <button class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
</form>

<!-- Filter Pills -->
<div class="flex gap-3 mb-6">

    <!-- ALL -->
    <a href="{{ route('admin.community.index') }}"
       class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        All
    </a>

    <!-- ACTIVE -->
    <a href="{{ route('admin.community.index', ['status' => 'active', 'search' => request('search')]) }}"
       class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Active
    </a>

    <!-- BLACKLISTED -->
    <a href="{{ route('admin.community.index', ['status' => 'blacklisted', 'search' => request('search')]) }}"
       class="px-5 py-2 rounded-full text-sm font-medium
              {{ request('status') == 'blacklisted' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Blacklisted
    </a>
</div>
      <div class="mb-4">
    <a href="{{ route('admin.community.export', array_merge(request()->only('search', 'status'), ['format' => 'csv'])) }}"
       class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        Export CSV
    </a>
</div>


<div class="bg-white shadow rounded-lg p-6">

    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-3 px-4">User</th>
                <th class="py-3 px-4">Phone</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($communityUsers as $user)
                <tr class="border-b hover:bg-gray-50">

                    <!-- USER -->
                    <td class="py-3 px-4 flex items-center gap-3">
                        <img src="{{ asset($user->profile_photo_path ?? 'uploads/profile/default.png') }}"
                             class="w-12 h-12 rounded-full object-cover border">

                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>

                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ Str::limit($user->bio, 50, '...') }}
                            </p>
                        </div>
                    </td>

                    <!-- PHONE -->
                    <td class="py-3 px-4 text-sm text-gray-700">
                        {{ $user->phone ?? '-' }}
                    </td>

                    <!-- STATUS -->
                    <td class="py-3 px-4 text-sm">
                        @if($user->is_blacklisted)
                            <span class="px-3 py-1 text-sm bg-red-200 text-red-800 rounded-full">Blacklisted</span>

                            @if($user->blacklist_reason)
                                <p class="text-xs text-red-700 mt-1">{{ $user->blacklist_reason }}</p>
                            @endif

                        @elseif($user->verification_status == 'approved')
                            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                                Verified
                            </span>

                        @else
                            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                                Not Verified
                            </span>
                        @endif
                    </td>

                    <!-- ACTIONS -->
                    <td class="py-3 px-4 text-sm whitespace-nowrap">
                        <div class="flex gap-3 items-center">

                        <a href="{{ route('admin.community.view', $user->id) }}" 
                           class="text-blue-600 hover:underline">View</a>

                        <a href="{{ route('admin.community.edit', $user->id) }}"
                           class="text-yellow-600 hover:underline">Edit</a>

                        @if(!$user->is_blacklisted)
                        <button onclick="openBlacklistModal({{ $user->id }})"
                            class="text-red-600 hover:underline">
                            Blacklist
                        </button>
                        @else
                        <form action="{{ route('admin.community.unblacklist', $user->id) }}" method="POST">
                            @csrf
                            <button class="text-green-600 hover:underline">Unblacklist</button>
                        </form>
                        @endif

                        <form action="{{ route('admin.community.delete', $user->id) }}" method="POST"
                              onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>

                    </td>
                </div>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $communityUsers->links() }}
    </div>

</div>



<!-- BLACKLIST MODAL -->
<div id="blacklistModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm
            flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-xl">

        <h2 class="text-xl font-bold mb-4">Blacklist User</h2>
        <p class="text-gray-600 mb-3">Please provide a reason:</p>

        <textarea id="blacklistReason" rows="3"
            class="w-full border rounded p-2 focus:ring focus:ring-red-300"
            placeholder="Write reason..."></textarea>

        <div class="mt-5 flex justify-end gap-3">
            <button onclick="closeBlacklistModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                Cancel
            </button>

            <button onclick="submitBlacklist()"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                Confirm
            </button>
        </div>
    </div>
</div>


<script>
    const csrfToken = "{{ csrf_token() }}";
    let selectedUserId = null;

    function openBlacklistModal(id) {
        selectedUserId = id;
        document.getElementById("blacklistModal").classList.remove("hidden");
    }

    function closeBlacklistModal() {
        document.getElementById("blacklistModal").classList.add("hidden");
        document.getElementById("blacklistReason").value = "";
    }

    function submitBlacklist() {
    const reason = document.getElementById("blacklistReason").value.trim();

    if (!reason) {
        alert("Please enter a blacklist reason.");
        return;
    }

    let form = document.createElement("form");
    form.method = "POST";
    form.action = "{{ route('admin.community.blacklist', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', selectedUserId);

    let token = document.createElement("input");
    token.type = "hidden";
    token.name = "_token";
    token.value = "{{ csrf_token() }}";
    form.appendChild(token);

    let reasonInput = document.createElement("input");
    reasonInput.type = "hidden";
    reasonInput.name = "blacklist_reason";
    reasonInput.value = reason;
    form.appendChild(reasonInput);

    document.body.appendChild(form);
    form.submit();
}

</script>

@endsection
