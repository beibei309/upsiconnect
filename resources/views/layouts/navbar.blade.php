  <!-- Navigation -->
  
        <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                 <div class="flex justify-between items-center h-30 px-6"> <!-- h-30 lebih tinggi -->
                    <div class="flex items-center h-full"> <!-- buat full height untuk logo & menu -->
                        <h1 class="text-4xl font-bold text-indigo-600">S2U</h1> <!-- logo lebih besar -->
                    </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('welcome') }}"
                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                                Home
                            </a>
                            <a href="{{ route('about') }}"
                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">About
                                Us</a>
                            <a href="{{ route('help') }}" 
=======
                            <a href="{{ route('home') }}"

                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                                Home
                            </a>
                            <a href="{{ route('about') }}"
                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">About
                                Us</a>
                            <a href="{{ route('help') }}"
                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Help</a>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Login</a>
                            <a href="{{ route('register') }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Sign Up
                            </a>
                        @endauth
                    </div>

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

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('home') }}"
                        class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="{{ route('about') }}"
                        class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">About
                        Us</a>
                    <a href="{{ route('help') }}"
                        class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Help
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-indigo-600 text-white block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white block px-3 py-2 rounded-md text-base font-medium">Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </nav>