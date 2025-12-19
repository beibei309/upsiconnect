<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | S2U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Smooth transition for sidebar width and transforms */
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="flex h-screen overflow-hidden">

    <!-- MENU -->
    <nav class="flex-1 overflow-y-auto mt-4">

        <ul class="space-y-1">

            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-6 py-3 hover:bg-blue-50 hover:text-blue-600 font-medium
                   {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700' }}">
                    Dashboard
                </a>
            </li>

            <li class="flex items-center justify-between">

    {{-- MAIN LINK --}}
    <a href="{{ route('admin.students.index') }}"
       class="flex-1 block px-6 py-3 font-medium
       {{ request()->routeIs('admin.students.index')
            ? 'bg-blue-100 text-blue-700 font-semibold'
            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
        Manage Students
    </a>

    {{-- TOGGLE BUTTON --}}
    <button type="button"
        onclick="toggleStudentMenu()"
        class="px-3 text-gray-500 hover:text-blue-600">
        <svg id="studentMenuArrow"
             class="w-4 h-4 transition-transform
             {{ request()->routeIs('admin.student_status.*') ? 'rotate-90' : '' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5l7 7-7 7" />
        </svg>
    </button>
</li>


            <ul id="studentSubMenu"
    class="ml-6 mt-1
    {{ request()->routeIs('admin.student_status.*') ? '' : 'hidden' }}">

    <li>
        <a href="{{ route('admin.student_status.index') }}"
           class="block px-6 py-2 text-sm rounded
           {{ request()->routeIs('admin.student_status.*')
                ? 'bg-blue-100 text-blue-700 font-semibold'
                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
            Student Status
        </a>
    </li>
</ul>


            <li class="flex items-center justify-between">
                <!-- MAIN LINK -->
                <a href="{{ route('admin.community.index') }}"
                   class="flex-1 block px-6 py-3 font-medium
                   {{ request()->routeIs('admin.community.index') || request()->routeIs('admin.community.view') || request()->routeIs('admin.community.edit')
                        ? 'bg-blue-100 text-blue-700 font-semibold'
                        : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    Manage Community
                </a>

                <!-- TOGGLE BUTTON -->
                <button type="button"
                    onclick="toggleCommunityMenu()"
                    class="px-3 text-gray-500 hover:text-blue-600">
                    <svg id="communityMenuArrow"
                         class="w-4 h-4 transition-transform
                         {{ request()->routeIs('admin.verifications.*') ? 'rotate-90' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </li>

            <!-- SUBMENU -->
            <ul id="communitySubMenu"
                class="ml-6 mt-1
                {{ request()->routeIs('admin.verifications.*') ? '' : 'hidden' }}">
                <li>
                    <a href="{{ route('admin.verifications.page') }}"
                       class="block px-6 py-2 text-sm rounded
                       {{ request()->routeIs('admin.verifications.*')
                            ? 'bg-blue-100 text-blue-700 font-semibold'
                            : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        Pending Verifications
                    </a>
                </li>
            </ul>

            <li>
                <a href="{{ route('admin.services.index') }}"
                   class="block px-6 py-3 hover:bg-blue-50 hover:text-blue-600 font-medium
                   {{ request()->routeIs('admin.services.*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700' }}">
                    Manage Services
                </a>
            </li>

          <li>
                <a href="{{ route('admin.requests.index') }}" 
                   class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium
                   {{ request()->routeIs('admin.requests.index') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700' }}">
                    <i class="fa fa-list"></i> Manage Requests
                </a>
            </li>

           
            <li class="nav-item">
                <a href="{{ route('admin.feedback.index') }}"
                   class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium
              {{ request()->routeIs('admin.feedback.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                    Feedback & Complaints
                </a>
            </li>

             <li>
                <a href="#"
                   class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                    Reports & Analytics
                </a>
            </li>
            
            <div class="flex items-center justify-between p-6 h-16 border-b border-gray-200 bg-white">
                <div>
                    <h1 class="text-2xl font-bold text-blue-600 tracking-wider">S2U</h1>
                    <p class="text-xs text-gray-500 uppercase tracking-widest">Admin</p>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-red-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">

                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                           {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <button onclick="toggleSubMenu('pageMenu', 'pageArrow')"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors
                                {{ request()->routeIs('admin.pages.*') ? 'text-blue-700 font-semibold bg-blue-50' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                <span>Page Management</span>
                            </div>
                            <svg id="pageArrow" class="w-4 h-4 transition-transform {{ request()->routeIs('admin.pages.*') ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        
                        <ul id="pageMenu" class="pl-11 mt-1 space-y-1 {{ request()->routeIs('admin.pages.*') ? '' : 'hidden' }}">
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-50 hover:text-blue-600 text-gray-600">About page</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.faqs.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-50 hover:text-blue-600 text-gray-600">Help page</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-50 hover:text-blue-600 text-gray-600">Menu Settings</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button onclick="toggleSubMenu('studentSubMenu', 'studentMenuArrow')"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors
                                {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.student_status.*') ? 'text-blue-700 font-semibold bg-blue-50' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>Students</span>
                            </div>
                            <svg id="studentMenuArrow" class="w-4 h-4 transition-transform {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.student_status.*') ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <ul id="studentSubMenu" class="pl-11 mt-1 space-y-1 {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.student_status.*') ? '' : 'hidden' }}">
                            <li>
                                <a href="{{ route('admin.students.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.students.index') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">All Students</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.student_status.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.student_status.index') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">Student Status</a>
                            </li>
                        </ul>
                    </li>
                     <li>
                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                           {{ request()->routeIs('admin.categories.index') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                           Categories
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.community.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                           {{ request()->routeIs('admin.community.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                           Manage Community
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.requests.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                           {{ request()->routeIs('admin.requests.index') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                           Requests
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.feedback.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                           {{ request()->routeIs('admin.feedback.index') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-blue-600' }}">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                           Feedback
                        </a>
                    </li>

                </ul>

                @if(auth('admin')->user()->role === 'superadmin')
                    <div class="px-6 py-4 mt-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Super Admin</p>
                    </div>
                    <ul class="space-y-1 px-3">
                        <li>
                            <a href="{{ route('admin.super.admins.index') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Admin Accounts
                            </a>
                        </li>
                    </ul>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                        {{ strtoupper(auth('admin')->user()->name[0]) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth('admin')->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-blue-600 focus:outline-none focus:bg-gray-100 p-2 rounded-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800">Admin Dashboard</h2>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 hidden sm:block">{{ now()->format('D, M d Y') }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-gray-50">
                @yield('content')
            </main>
        </div>

<!-- Auto-display SweetAlert for Laravel flash messages -->
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        showConfirmButton: true
    });
@endif
</script>

</body>
</html>

    </div>

    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-90');
}

function toggleCommunityMenu() {
    const menu = document.getElementById('communitySubMenu');
    const arrow = document.getElementById('communityMenuArrow');

    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-90');
}
</script>
</body>
</html>