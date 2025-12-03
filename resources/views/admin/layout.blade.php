<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | S2U</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f4f6f9;
        }
    </style>
</head>

<body class="min-h-screen flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-lg border-r border-gray-200 fixed h-full flex flex-col justify-between">

    <div>
        <!-- LOGO -->
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-blue-600">S2U</h1>
            <p class="text-gray-500 text-sm">Admin Panel</p>
        </div>

        <!-- MENU -->
        <nav class="mt-4">

            {{-- COMMON MENU FOR ALL ADMINS --}}
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.students.index') }}"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Manage Students
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Manage Users
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.services.index') }}"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Manage Services
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Manage Requests
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Reports & Analytics
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                        Feedback & Complaints
                    </a>
                </li>
            </ul>

            {{-- SUPERADMIN ONLY MENU --}}
            @if(auth('admin')->user()->role === 'superadmin')
                <ul class="space-y-1 mt-6 border-t border-gray-200 pt-4">

                    <li class="px-6 py-2 text-gray-500 uppercase text-xs tracking-wide">
                        SuperAdmin Tools
                    </li>

                    <li>
                        <a href="{{ route('admin.super.admins.index') }}"
                           class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                            Manage Admin Accounts
                        </a>
                    </li>

                    <li>
                        <a href="#"
                           class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                            System Logs
                        </a>
                    </li>

                </ul>
            @endif

        </nav>
    </div>

    <!-- SIDEBAR PROFILE + LOGOUT -->
<div class="p-4 border-t border-gray-300 flex items-center justify-between">

    <!-- LEFT SIDE: Avatar + Name -->
    <div class="flex items-center gap-3">

        <!-- Small Avatar -->
        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-semibold">
            {{ strtoupper(auth('admin')->user()->name[0]) }}
        </div>

        <!-- Name (truncated if long) -->
        <span class="text-sm font-semibold text-gray-800 truncate max-w-[90px]">
            {{ auth('admin')->user()->name }}
        </span>

    </div>

    <!-- RIGHT SIDE: Logout -->
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="text-red-600 text-sm hover:text-red-800 font-medium">
            Logout
        </button>
    </form>

</div>


</aside>

<!-- MAIN CONTENT -->
<main class="ml-64 flex-1 p-8">
    @yield('content')
</main>

@yield('scripts')

</body>
</html>
