@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-2xl shadow border border-gray-200">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Manage Admin Accounts</h2>

        <a href="{{ route('admin.super.admins.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
           + Add Admin
        </a>
    </div>

    <table class="w-full mt-4">
        <thead>
            <tr class="border-b">
                <th class="py-3 text-left">Name</th>
                <th class="py-3 text-left">Email</th>
                <th class="py-3 text-left">Role</th>
                <th class="py-3 text-left">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($admins as $admin)
            <tr class="border-b">
                <td class="py-3">{{ $admin->name }}</td>
                <td class="py-3">{{ $admin->email }}</td>
                <td class="py-3 capitalize">{{ $admin->role }}</td>

                <td class="py-3 flex gap-3">
                    <a href="{{ route('admin.super.admins.edit', $admin->id) }}"
                       class="text-blue-600 hover:underline">Edit</a>

                    <form action="{{ route('admin.super.admins.delete', $admin->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete admin account?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection
