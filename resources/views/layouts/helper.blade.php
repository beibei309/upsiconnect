<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="upsi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('S2U', 'S2U - Student to Community') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        nav {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f3f4f6;
        }

        /* Navigation Link Styles */
        .nav-link {
            @apply px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 cursor-pointer;
            color: #4b5563;
        }

        .nav-link:hover {
            color: #0d9488 !important;
            background-color: #f0fdfa;
        }

        .nav-link.active {
            color: #0f766e !important;
            background-color: #f0fdfa;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">

        <nav x-data="{ mobileMenuOpen: false, userOpen: false }" class="bg-white fixed w-full top-0 z-50 h-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex justify-between items-center h-full">

                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center">
                            <h1 class="text-3xl font-bold text-indigo-600 tracking-tight">S2U</h1>
                            <span
                                class="ml-2 px-2 py-0.5 rounded text-xs font-semibold bg-indigo-100 text-indigo-700">Helper</span>
                        </a>

                        <div class="hidden md:ml-10 md:flex md:space-x-10 items-center">
                            <a href="{{ route('students.index') }}"
                                class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('services.manage') }}"
                                class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                                My Services
                            </a>


                            <a href="{{ route('service-requests.index') }}"
                                class="nav-link {{ request()->routeIs('service-requests.*') ? 'active' : '' }}">
                                Orders
                            </a>

                        </div>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        @auth
    

                            <button type="button" class="relative p-2 text-gray-400 hover:text-gray-500 transition ml-2">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 7 8.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L5 17h10z" />
                                </svg>
                                <span
                                    class="absolute top-2 right-2 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            </button>

                            <a href="{{ route('chat.index') }}" class="p-2 text-gray-400 hover:text-gray-500 transition">
                                <span class="sr-only">Messages</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </a>
                                                   @if (auth()->user()->role === 'helper')
                                <a href="{{ route('dashboard') }}"
                                    class="group relative inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-green-600 transition-all duration-200 bg-green-50 border border-green-200 rounded-full hover:bg-green-600 hover:text-white hover:border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                                    <span class="mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </span>
                                    Switch to Buying
                                </a>
                            @endif

                            <div class="relative ml-3" x-data="{ userOpen: false }">
                                <button @click="userOpen = !userOpen"
                                    class="flex items-center max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <img class="h-9 w-9 rounded-full object-cover border border-gray-200 shadow-sm"
                                        src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                        alt="">
                                </button>

                                <div x-show="userOpen" @click.away="userOpen = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1" style="display: none;">

                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors"
                                        role="menuitem">
                                        Your Profile
                                    </a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors"
                                        role="menuitem">
                                        Settings
                                    </a>

                                    <div class="border-t border-gray-100 mt-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                            role="menuitem">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex space-x-4">
                                <a href="{{ route('login') }}"
                                    class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    Log in
                                </a>
                                <a href="{{ route('register') }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors">
                                    Sign up
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="-mr-2 flex md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                            class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" class="block h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" class="hidden h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white shadow-lg"
                id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('students.index') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('students.index') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                    <a href="{{ route('services.manage') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('services.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }}">My
                        Services</a>
                    <a href="#"
                        class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700">Orders</a>

                </div>

                @auth
                    <div class="pt-4 pb-4 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full"
                                    src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                    alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            @if (auth()->user()->role === 'helper')
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-base font-medium text-green-600 hover:text-green-800 hover:bg-gray-100">
                                    Switch to Buying
                                </a>
                            @endif


                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50">Sign
                                    out</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
