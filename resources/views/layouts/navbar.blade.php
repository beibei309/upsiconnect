<!-- AlpineJS -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav x-data="{ mobileMenuOpen: false, userOpen: false }" class="bg-white shadow-sm fixed w-full top-0 z-50">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <h1 class="text-4xl font-bold text-indigo-600">S2U</h1>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Main Links -->
                <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}"
                    class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Home</a>

                <a href="{{ route('services.index') }}"
                    class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Find
                    Services</a>

                <a href="{{ route('about') }}"
                    class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">About
                    Us</a>

                <a href="{{ route('help') }}"
                    class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Help</a>
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
                        <span
                            class="absolute -top-0.5 -right-0.5 px-1.5 py-0.5 text-xs text-white bg-red-600 rounded-full">3</span>
                    </button>

                    <!-- Chat -->
                    <a href="{{ route('chat.index') }}" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                        </svg>
                    </a>

                    <!-- Favorites -->
                    <a href="{{ route('favorites.index') }}" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path stroke="none"
                                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                        </svg>
                    </a>
                
          @if (auth()->user()->role === 'student')
    <a href="{{ route('onboarding.students') }}" 
       class="relative p-2 rounded-md hover:bg-gray-100 transition"
       style="color: green; background-color: white; border: 1px solid green; padding: 8px 16px;">
        Join as Part-timer
    </a>
@elseif (auth()->user()->role === 'helper')
    <a href="{{ route('students.index') }}" 
       class="relative p-2 rounded-md hover:bg-gray-100 transition"
       style="color: black; background-color: white; border: 1px solid black; padding: 8px 16px;">
        Dashboard
    </a>
@endif


                    <!-- Avatar Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex items-center space-x-2">
                            <img class="h-10 w-10 rounded-full border-2 border-upsi-gold"
                                src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}">
                            <svg class="w-3 h-3 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.12 1l-4.25 4.66a.75.75 0 01-1.12 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="userOpen" @click.away="userOpen = false" x-transition
                            class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-lg ring-1 ring-black/5 p-2 z-50 text-black">

                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">Profile</a>

                            {{-- @if (auth()->user()->isCommunity()) --}}
                            <a href="{{ route('search.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">Browse Services</a>
                            <a href="{{ route('favorites.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">My Favorites</a>
                            <a href="{{ route('service-requests.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">My Service Requests</a>
                            <a href="{{ route('services.applications.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">My Applications</a>
                            <a href="{{ route('services.apply') }}"
                                class="block px-4 py-2 hover:bg-gray-100 rounded-md">Request Custom Service</a>
                            {{-- @endif --}}

                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t pt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-md">Sign
                                    out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest -->
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Sign Up</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t">
        <div class="px-4 pt-4 pb-6 space-y-1">
            <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Home</a>
            <a href="{{ route('services.index') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Find Services</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">About
                Us</a>
            <a href="{{ route('help') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Help</a>

            @auth
                <div class="border-t pt-4 space-y-1">
                    <a href="{{ route('chat.index') }}"
                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Messages</a>
                    <a href="{{ route('favorites.index') }}"
                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Favorites</a>
                    <a href="{{ route('service-requests.index') }}"
                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Service Requests</a>
                    <a href="{{ route('profile.edit') }}"
                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">Sign Out</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Login</a>
                <a href="{{ route('register') }}"
                    class="block px-3 py-2 bg-indigo-600 text-white rounded-md text-center hover:bg-indigo-700">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>
