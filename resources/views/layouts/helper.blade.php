<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="upsi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('S2U', 'S2U - Student to Community') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white">
    <div class="min-h-screen bg-white">
        
        <!-- AlpineJS -->
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <style>
            nav {
                background-color: #ffffff !important;
            }

            nav a:hover {
                color: #367588 !important; /* Yellow hover */
            }
        </style>

        <!-- Navigation Bar -->
        <nav x-data="{ mobileMenuOpen: false, userOpen: false }" class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    
                    <!-- Logo -->
                    <div class="flex items-center">
                        <h1 class="text-4xl font-bold text-indigo-600">S2U</h1>
                    </div>

                    <!-- Desktop Menu -->
                   <div class="hidden md:flex items-left space-x-4">
    <a href="{{ route('students.index') }}"
       class="px-3 py-2 text-gray-800 text-m font-medium rounded-md hover:text-indigo-600 transition">
       Dashboard
    </a>
    <a href="{{ route('services.manage') }}"
       class="px-3 py-2 text-gray-800 text-m font-medium rounded-md hover:text-indigo-600 transition">
       My Services
    </a>
</div>


                    <!-- Desktop Right Section -->
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <!-- Notification -->
                            <button type="button" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 7 8.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L5 17h10z" />
                                </svg>
                                <span class="absolute -top-0.5 -right-0.5 px-1.5 py-0.5 text-xs text-white bg-red-600 rounded-full">3</span>
                            </button>

                            <!-- Chat -->
                            <a href="{{ route('chat.index') }}" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                </svg>
                            </a>

                            <!-- Role-specific links -->
                            @if (auth()->user()->role === 'student')
                                <a href="{{ route('onboarding.students') }}" class="relative p-2 rounded-md hover:bg-gray-100 transition" style="color: green; background-color: white; border: 1px solid green; padding: 8px 16px;">
                                    Join as Part-timer
                                </a>
                            @elseif (auth()->user()->role === 'helper')
                                <a href="{{ route('dashboard') }}" class="relative p-2 rounded-md border border-gray-700 bg-white text-black hover:bg-gray-100 hover:text-white transition">
                                    Switch to buying
                                </a>
                            @endif

                            <!-- Avatar Dropdown -->
                            <div class="relative" x-data="{ userOpen: false }">
                                <button @click="userOpen = !userOpen" class="flex items-center space-x-2">
                                    <img class="h-10 w-10 rounded-full border-2 border-upsi-gold" src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}">
                                    <svg class="w-3 h-3 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.12 1l-4.25 4.66a.75.75 0 01-1.12 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="userOpen" @click.away="userOpen = false" x-transition class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-lg ring-1 ring-black/5 p-2 z-50 text-black">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-md">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t pt-2">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-md">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Guest -->
                            <a href="{{ route('login') }}" class="hover:text-indigo-600 px-3 py-2 text-gray-800 rounded-md text-m">Login</a>
                            <a href="{{ route('register') }}" class="bg-custom-teal text-white-100 px-4 py-2 rounded-lg hover:bg-indigo-700">Sign Up</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="hover:text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t">
                <div class="px-4 pt-4 pb-6 space-y-1">
                 
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-1 bg-white">
            @yield('content')
        </div>
    </div>

    <script>
        // Initialize notification system
        let unreadCount = 0;

        // Function to update notification badges
        function updateNotificationBadges(count) {
            const desktopBadge = document.getElementById('messageNotificationBadge');
            const mobileBadge = document.getElementById('mobileMessageNotificationBadge');

            if (count > 0) {
                if (desktopBadge) {
                    desktopBadge.textContent = count;
                    desktopBadge.classList.remove('hidden');
                }
                if (mobileBadge) {
                    mobileBadge.textContent = count;
                    mobileBadge.classList.remove('hidden');
                }
            } else {
                if (desktopBadge) {
                    desktopBadge.classList.add('hidden');
                }
                if (mobileBadge) {
                    mobileBadge.classList.add('hidden');
                }
            }
        }
    </script>
    @stack('scripts')

</body>

</html>
