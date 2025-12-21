@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Manage Community Users</h1>

<!-- Search + Export Row -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">

    <!-- Search -->
    <form method="GET" class="flex items-center gap-2 w-full md:w-auto">
        <input type="text"
               name="search"
               placeholder="Search community users..."
               class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
               value="{{ request('search') }}">

        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <button class="px-5 py-2 bg-blue-600 text-white rounded-lg
                       hover:bg-blue-700 transition text-sm font-medium">
            Search
        </button>
    </form>

    <!-- Export -->
    <a href="{{ route('admin.community.export', array_merge(request()->only('search', 'status'), ['format' => 'csv'])) }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600
              text-white rounded-lg hover:bg-green-700 transition text-sm">
        <i class="fa-solid fa-file-csv"></i>
        Export CSV
    </a>

</div>

<!-- FILTER PILLS -->
<div class="flex flex-wrap gap-2 mb-6">

    @php
        $pill = 'px-4 py-2 rounded-full text-sm font-medium transition';
        $active = 'bg-blue-600 text-white';
        $inactive = 'bg-gray-100 text-gray-700 hover:bg-gray-200';
    @endphp

    <!-- ALL -->
    <a href="{{ route('admin.community.index', request()->except('status')) }}"
       class="{{ $pill }} {{ request('status') == null ? $active : $inactive }}">
        All
    </a>

    <!-- ACTIVE -->
    <a href="{{ route('admin.community.index', ['status' => 'active'] + request()->except('page')) }}"
       class="{{ $pill }} {{ request('status') == 'active' ? $active : $inactive }}">
        Active
    </a>

    <!-- BLACKLISTED -->
    <a href="{{ route('admin.community.index', ['status' => 'blacklisted'] + request()->except('page')) }}"
       class="{{ $pill }} {{ request('status') == 'blacklisted' ? $active : $inactive }}">
        Blacklisted
    </a>

</div>


<div class="bg-white shadow rounded-lg p-6">

    <table class="w-full text-center">
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
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('uploads/profile/default.png') }}"
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
                    <!-- ACTIONS -->
<!-- ACTIONS -->
<td class="py-3 px-4 text-sm text-center whitespace-nowrap">
    <div class="flex justify-center gap-3 items-center">

        {{-- VIEW --}}
        <a href="{{ route('admin.community.view', $user->id) }}"
           class="text-indigo-600 hover:text-indigo-900 transition"
           title="View">
            <i class="fa-solid fa-eye"></i>
        </a>

        {{-- EDIT --}}
        <a href="{{ route('admin.community.edit', $user->id) }}"
           class="text-blue-600 hover:text-blue-900 transition"
           title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        {{-- BLACKLIST / UNBLACKLIST --}}
        @if(!$user->is_blacklisted)
            <button onclick="openBlacklistModal({{ $user->id }})"
                    class="text-red-600 hover:text-red-900 transition"
                    title="Blacklist">
                <i class="fa-solid fa-ban"></i>
            </button>
        @else
            <form action="{{ route('admin.community.unblacklist', $user->id) }}"
                  method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="text-green-600 hover:text-green-900 transition"
                        title="Unblacklist">
                    <i class="fa-solid fa-unlock"></i>
                </button>
            </form>
        @endif

        {{-- DELETE --}}
        <form action="{{ route('admin.community.delete', $user->id) }}"
              method="POST"
              class="inline"
              onsubmit="return confirm('Delete this user?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-red-700 hover:text-red-900 transition"
                    title="Delete">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>

    </div>
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
