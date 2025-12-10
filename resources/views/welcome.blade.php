<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S2U - Student to Community</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Slate-50 */
        }
        h1, h2, h3, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Custom Scrollbar for horizontal scrolling */
        .hide-scroll-bar::-webkit-scrollbar {
            display: none;
        }
        .hide-scroll-bar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hero-overlay {
            background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="antialiased text-slate-800">
    <div x-data="{
        mobileMenuOpen: false,
        activeTab: 'seekers'
    }">

        {{-- Navigation bar --}}
        @include('layouts.navbar')

        <section class="relative min-h-[85vh] flex items-center justify-start overflow-hidden">
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover z-0">
                <source src="{{ asset('videos/herobanner.mp4') }}" type="video/mp4">
            </video>

            <div class="absolute inset-0 z-10 hero-overlay"></div>

            <div class="relative z-20 w-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-20">
                <div class="max-w-3xl animate-fade-in-up">
                    <h1 class="text-5xl md:text-6xl font-bold text-white leading-tight mb-6">
                        UPSI Student to Community<br>
                        <span class="text-orange-400">We've Got You.</span>
                    </h1>
                    <p class="text-lg text-gray-200 mb-8 max-w-2xl font-light">
                        Connect with talented students for services ranging from academic help to creative tasks. Secure, reliable, and community-driven.
                    </p>

                    <div class="bg-white p-2 rounded-2xl shadow-2xl max-w-2xl mb-6 flex items-center">
                        <form action="{{ route('services.index') }}" method="GET" class="w-full flex items-center">
                            <div class="pl-4 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="q" placeholder="What service are you looking for?" 
                                class="w-full py-3 px-4 text-gray-700 bg-transparent border-none focus:ring-0 focus:outline-none placeholder-gray-400 text-lg" />
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-3 rounded-xl font-semibold transition-all">
                                Search
                            </button>
                        </form>
                    </div>

                    <div class="flex flex-wrap gap-3 text-sm text-white/90">
                        <span class="py-1">Popular:</span>
                        <a href="{{ route('services.index', ['q' => 'iron baju']) }}" class="px-3 py-1 rounded-full border border-white/30 hover:bg-white/10 transition backdrop-blur-sm cursor-pointer">Iron Baju</a>
                        <a href="{{ route('services.index', ['q' => 'video editing']) }}" class="px-3 py-1 rounded-full border border-white/30 hover:bg-white/10 transition backdrop-blur-sm cursor-pointer">Video Editing</a>
                        <a href="{{ route('services.index', ['q' => 'poster design']) }}" class="px-3 py-1 rounded-full border border-white/30 hover:bg-white/10 transition backdrop-blur-sm cursor-pointer">Poster Design</a>
                        <a href="{{ route('services.index', ['q' => 'pickup']) }}" class="px-3 py-1 rounded-full border border-white/30 hover:bg-white/10 transition backdrop-blur-sm cursor-pointer">Pickup Parcel</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white relative">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Explore Categories</h2>
                        <p class="text-gray-500 mt-2">Find exactly what you need.</p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button id="scrollLeft" class="p-2 rounded-full border hover:bg-gray-50 transition text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                        <button id="scrollRight" class="p-2 rounded-full border hover:bg-gray-50 transition text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                    </div>
                </div>

                <div id="scrollContainer" class="flex gap-6 overflow-x-auto hide-scroll-bar pb-4 snap-x snap-mandatory">
                    @foreach ($categories ?? [] as $category)
                        <a href="{{ route('services.index', ['category_id' => $category->id]) }}" 
                           class="snap-center shrink-0 w-64 p-6 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group cursor-pointer"
                           style="border-bottom: 3px solid {{ $category->color }};">
                            
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110"
                                 style="background-color: {{ $category->color }}15;">
                                <img src="{{ asset('images/' . $category->image_path) }}" alt="{{ $category->name }}" class="w-8 h-8 object-contain">
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $category->description }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why choose S2U?</h2>
                    <p class="text-gray-500">We create a safe, reliable environment for students to connect and collaborate.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Students</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Every service provider is a verified UPSI student. We ensure safety and legitimacy so you can hire with confidence.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transparent Pricing</h3>
                        <p class="text-gray-600 leading-relaxed">
                            No hidden fees or commissions. What you see is what you pay. Affordable rates tailored for the student community.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Community Support</h3>
                        <p class="text-gray-600 leading-relaxed">
                            By using S2U, you directly support your peers' financial independence and skill development.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-white">
            <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How it works</h2>
                    <div class="flex justify-center gap-4 mt-6">
                        <button @click="activeTab = 'seekers'" 
                            :class="activeTab === 'seekers' ? 'bg-slate-900 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-6 py-2 rounded-full font-semibold transition-all">
                            For Buyers
                        </button>
                        <button @click="activeTab = 'providers'" 
                            :class="activeTab === 'providers' ? 'bg-slate-900 text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-6 py-2 rounded-full font-semibold transition-all">
                            For Students
                        </button>
                    </div>
                </div>

                <div x-show="activeTab === 'seekers'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                        <div class="relative">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">1</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Search & Browse</h3>
                            <p class="text-gray-500">Filter by category, price, or rating to find the perfect match for your needs.</p>
                        </div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">2</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Connect Directly</h3>
                            <p class="text-gray-500">Chat with the provider to discuss details, deadlines, and requirements.</p>
                        </div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">3</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Get it Done</h3>
                            <p class="text-gray-500">Receive your service or completed task and leave a review for the community.</p>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'providers'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                        <div class="relative">
                            <div class="w-16 h-16 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">1</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Create Profile</h3>
                            <p class="text-gray-500">Sign up with your student ID, complete your bio, and verify your status.</p>
                        </div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">2</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">List Services</h3>
                            <p class="text-gray-500">Post your services with clear descriptions, pricing, and attractive images.</p>
                        </div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-6">3</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Start Earning</h3>
                            <p class="text-gray-500">Accept requests, deliver quality work, and get paid directly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-slate-900 text-white text-center">
            <div class="max-w-4xl mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to get started?</h2>
                <p class="text-gray-400 text-lg mb-8 max-w-2xl mx-auto">
                    Join hundreds of students connecting, learning, and earning on S2U today.
                </p>
                <div class="flex justify-center gap-4">
                    @auth
                        <a href="{{ route('services.index') }}" class="bg-white text-slate-900 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg">
                            Find Services
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-slate-900 px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg">
                            Join Now - It's Free
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-3 rounded-xl font-bold border border-gray-700 hover:bg-gray-800 transition">
                            Log In
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>

    <script>
        const scrollContainer = document.getElementById('scrollContainer');
        const scrollLeftButton = document.getElementById('scrollLeft');
        const scrollRightButton = document.getElementById('scrollRight');

        scrollLeftButton.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: -300, behavior: 'smooth' });
        });

        scrollRightButton.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: 300, behavior: 'smooth' });
        });
    </script>
</body>
</html>