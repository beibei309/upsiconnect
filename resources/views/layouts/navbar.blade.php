  <!-- Navigation -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


  <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16">
              <div class="flex justify-between items-center h-30 px-6"> <!-- h-30 lebih tinggi -->
                  <div class="flex items-center h-full"> <!-- buat full height untuk logo & menu -->
                      <h1 class="text-4xl font-bold text-indigo-600">S2U</h1> <!-- logo lebih besar -->
                  </div>
              </div>

              <div class="hidden md:block">
                  <div class="ml-10 flex items-baseline space-x-4">
                      @guest
                          <a href="{{ route('home') }}"
                              class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                              Home
                          </a>
                      @endguest

                      @auth
                          <a href="{{ route('dashboard') }}"
                              class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                              Home
                          </a>
                      @endauth

                          <a href="{{ route('services.index') }}"
                              class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                              Find services
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
                      {{-- AUTH BLOCK START --}}

                      <!-- Notification Icon -->
                      <button type="button" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 7 8.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L5 17h10z" />
                          </svg>
                          <span
                              class="absolute -top-0.5 -right-0.5 px-1.5 py-0.5 text-xs text-white bg-red-600 rounded-full">3</span>
                      </button>

                      <!-- Chat Icon -->
                        <a href="{{ route('chat.index') }}" class="inline-block">
                      <button type="button" class="relative p-2 rounded-md hover:bg-gray-100 transition">
                          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                          </svg>
                      </button>
                      </a>

                      <!-- Favorites -->
                      <a href="{{ route('favorites.index') }}" class="inline-block">
                          <button class="p-2 rounded-md hover:bg-gray-100 transition">
                              <svg class="w-5 h-5 text-gray-600" fill="red" stroke="red" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.182 4.318 12.682a4.5 4.5 0 010-6.364z" />
                              </svg>
                          </button>
                      </a>

                      <a href="#" class="text-gray-600 hover:text-gray-800 font-medium px-2">Orders</a>

                      <!-- Avatar Dropdown -->
                      <div class="relative" x-data="{ userOpen: false }">
                          <button @click="userOpen = !userOpen" class="flex items-center space-x-2">
                              <div class="relative">
                                  <img class="h-10 w-10 rounded-full border-2 border-upsi-gold"
                                      src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=ffffff&background=C41E3A">
                                  <span
                                      class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-400 rounded-full ring-2 ring-white"></span>
                              </div>

                              <svg class="w-3 h-3 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 011.12 1l-4.25 4.66a.75.75 0 01-1.12 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd" />
                              </svg>
                          </button>

                          <div x-show="userOpen" @click.away="userOpen = false"
                              class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-lg ring-1 ring-black/5 p-2"
                              style="display:none; color:black;">
                              <a href="{{ route('profile.edit') }}"
                                  class="block px-4 py-2 hover:bg-gray-100 rounded-md">Profile</a>

                              <a href="{{ route('service-requests.index') }}"
                              class="block px-4 py-2 hover:bg-gray-100 rounded-md">Service Request</a>

                              <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t">
                                  @csrf
                                  <button type="submit"
                                      class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-md">Sign
                                      out</button>
                              </form>
                          </div>
                      </div>

                      {{-- AUTH BLOCK END --}}
                  @else
                      <a href="{{ route('login') }}"
                          class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm">
                          Login
                      </a>
                      <a href="{{ route('register') }}"
                          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                          Sign Up
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
          <div class="px-4 pt-4 pb-6 space-y-1">

              <!-- Main Nav -->
              <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                  Home
              </a>

              <a href=""
                  class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                  Find Services
              </a>

              <a href="{{ route('services.index') }}"
              class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                  About Us
              </a>

              <a href="{{ route('help') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                  Help
              </a>

              @auth

                  <!-- Icons Section (Mobile) -->
                  <div class="border-t pt-4">

                      <!-- Notification -->
                      <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 7 8.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L5 17h10z" />
                          </svg>
                          Notifications
                          <span class="ml-auto bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">3</span>
                      </a>

                      <!-- Chat -->
                      <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                          </svg>
                          Messages
                          <span class="ml-auto bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">2</span>
                      </a>

                      <!-- Favorites -->
                      <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.182 4.318 12.682a4.5 4.5 0 010-6.364z" />
                          </svg>
                          Favorites
                      </a>

                      <!-- Orders -->
                      <a href="#" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M10 21a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />
                          </svg>
                          Orders
                      </a>
                  </div>

                  <!-- User Options -->
                  <div class="border-t pt-4 space-y-1">
                      <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Profile</a>
                      <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Post a project
                          brief</a>

                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button type="submit"
                              class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">
                              Sign Out
                          </button>
                      </form>
                  </div>
              @else
                  <!-- If guest -->
                  <a href="{{ route('login') }}"
                      class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Login</a>

                  <a href="{{ route('register') }}"
                      class="block px-3 py-2 bg-indigo-600 text-white rounded-md text-center hover:bg-indigo-700">
                      Sign Up
                  </a>

              @endauth

          </div>
      </div>
  </nav>
