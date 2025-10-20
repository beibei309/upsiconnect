<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UpsiConnect') }} - Connect, Learn, Grow</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="antialiased">
    <div x-data="{ 
        mobileMenuOpen: false, 
        activeTab: 'students',
        stats: { students: 1250, services: 340, reviews: 890 },
        animateStats: false
    }" x-init="setTimeout(() => animateStats = true, 1000)">
        
        <!-- Navigation -->
        <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold text-indigo-600">UpsiConnect</h1>
                        </div>
                    </div>
                    
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="#features" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Features</a>
                            <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">How It Works</a>
                            <a href="#stats" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Community</a>
                        </div>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">Login</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
                        @endauth
                    </div>
                    
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">How It Works</a>
                    <a href="#stats" class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Community</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white block px-3 py-2 rounded-md text-base font-medium">Get Started</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="gradient-bg pt-20 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Connect. Learn. <span class="text-yellow-300">Grow Together.</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                        UpsiConnect is the premier platform connecting UPSI students with peer-to-peer services. 
                        Find tutoring, design help, coding assistance, and more from your fellow students.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ route('search.index') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105">
                                Explore Services
                            </a>
                            <a href="{{ route('dashboard') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105">
                                Join UpsiConnect
                            </a>
                            <a href="{{ route('search.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                                Browse Services
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.students : 0"></div>
                        <div class="text-gray-600 font-medium">Active Students</div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.services : 0"></div>
                        <div class="text-gray-600 font-medium">Services Available</div>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <div class="text-4xl font-bold text-indigo-600 mb-2" x-text="animateStats ? stats.reviews : 0"></div>
                        <div class="text-gray-600 font-medium">Positive Reviews</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose UpsiConnect?</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Built by students, for students. Our platform makes it easy to find help and offer your skills.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Trusted Community</h3>
                        <p class="text-gray-600">All users are verified UPSI students. Build trust through our rating and review system.</p>
                    </div>
                    
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Instant Connections</h3>
                        <p class="text-gray-600">Find help quickly with our smart search and instant messaging system.</p>
                    </div>
                    
                    <div class="text-center p-8 card-hover bg-gray-50 rounded-xl">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Fair Pricing</h3>
                        <p class="text-gray-600">Student-friendly rates with transparent pricing. No hidden fees or commissions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How UpsiConnect Works</h2>
                    <p class="text-xl text-gray-600">Simple steps to connect with your peers</p>
                </div>
                
                <!-- Tab Navigation -->
                <div class="flex justify-center mb-12">
                    <div class="bg-white p-1 rounded-lg shadow-sm">
                        <button @click="activeTab = 'students'" 
                                :class="activeTab === 'students' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'"
                                class="px-6 py-3 rounded-md font-medium transition">
                            For Students Seeking Help
                        </button>
                        <button @click="activeTab = 'providers'" 
                                :class="activeTab === 'providers' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'"
                                class="px-6 py-3 rounded-md font-medium transition">
                            For Service Providers
                        </button>
                    </div>
                </div>
                
                <!-- Tab Content -->
                <div x-show="activeTab === 'students'" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h3 class="text-lg font-semibold mb-2">Search & Browse</h3>
                        <p class="text-gray-600">Use our smart filters to find exactly what you need - tutoring, design, coding, and more.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h3 class="text-lg font-semibold mb-2">Connect & Chat</h3>
                        <p class="text-gray-600">Send a chat request to discuss your needs, timeline, and pricing with the service provider.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h3 class="text-lg font-semibold mb-2">Learn & Review</h3>
                        <p class="text-gray-600">Get the help you need and leave a review to help other students make informed decisions.</p>
                    </div>
                </div>
                
                <div x-show="activeTab === 'providers'" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h3 class="text-lg font-semibold mb-2">Create Your Profile</h3>
                        <p class="text-gray-600">Set up your services, showcase your skills, and set your availability status.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h3 class="text-lg font-semibold mb-2">Receive Requests</h3>
                        <p class="text-gray-600">Get notified when students are interested in your services and start conversations.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h3 class="text-lg font-semibold mb-2">Earn & Grow</h3>
                        <p class="text-gray-600">Help fellow students while earning money and building your reputation in the community.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 gradient-bg">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Join the Community?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Whether you need help with your studies or want to share your skills, UpsiConnect is here for you.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105 inline-block">
                        Go to Your Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition transform hover:scale-105 inline-block">
                        Get Started Today
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <h3 class="text-2xl font-bold mb-4">UpsiConnect</h3>
                        <p class="text-gray-400 mb-4">
                            Connecting UPSI students through peer-to-peer services. 
                            Built by students, for students.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="{{ route('search.index') }}" class="hover:text-white transition">Browse Services</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('register') }}" class="hover:text-white transition">Sign Up</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition">Community Guidelines</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} UpsiConnect. Built with ❤️ for UPSI students.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
