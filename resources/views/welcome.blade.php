<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S2U - Student to Community</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        input::placeholder {
            color: white;
            opacity: 1;
        }

        select option {
            border-radius: 10px;
            color: #484745;
            background-color: #F0F0F0;

        }

        .step-number {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: #367588;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 50px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .tab-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .tab-btn {
            padding: 0.75rem 2rem;
            border: 2px solid #367588;
            background: white;
            color: #367588;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: #367588;
            color: white;
        }
    </style>
</head>

<body class="antialiased">
    <div x-data="{
        mobileMenuOpen: false,
        activeTab: 'seekers',
        stats: { students: 1250, services: 340, reviews: 890 },
        animateStats: false
    }" x-init="setTimeout(() => animateStats = true, 1000)">

        {{-- Navigation bar --}}
        @include('layouts.navbar')


        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center overflow-hidden pt-36">

            <!-- Background video -->
            <video autoplay muted loop playsinline
                class="absolute inset-0 w-full h-full object-cover z-0 brightness-20">
                <source src="{{ asset('videos/herobanner.mp4') }}" type="video/mp4">
            </video>


            <!-- Left-side dark overlay using gradient -->
            <div class="absolute inset-0 z-10"
                style="background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
            </div>

            <!-- CONTENT -->
            <div class="relative z-20 w-full max-w-6xl mx-auto px-0.2">
                <h1
                    style="color: white; 
           font-family: 'Poppins', sans-serif; 
           font-size: 50px;
           font-weight: 200; 
           max-width: 48rem;
           line-height: 1.25; 
            margin-bottom: 1.5rem; /* reduce spacing below */
           margin-top: -50px;">
                    UPSI student to community<br>
                    <span style="color: #cd7a3f;">we've got you.</span>
                </h1>

                <!-- SEARCH BAR -->
                <div class="w-full max-w-5xl">
                    <form action="{{ route('services.index') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Search for any service..."
                                class="w-full py-4 pl-5 pr-14 rounded-xl text-lg shadow-lg focus:outline-none text-gray-900 placeholder-gray-400" />

                            <!-- Search Icon -->
                            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2">
                                <div class=" w-12 h-12 rounded-xl shadow flex items-center justify-center"
                                    style="background-color: #23221e;">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search idea -->
                <div class="flex gap-5 mt-6 flex-wrap">
                    <a href="{{ route('services.index', ['q' => 'iron baju']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition">
                        iron baju →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'video editing']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition">
                        video editing →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'booth helper']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition">
                        booth helper →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'design poster']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium backdrop-blur transition">
                        design poster →
                    </a>
                    <a href="{{ route('services.index', ['q' => 'pickup parcel']) }}"
                        class="bg-transparent border border-white hover:bg-white/10 px-8 py-2 rounded-md text-white text-sm font-medium flex items-center gap-2 backdrop-blur transition">
                        pickup parcel →
                    </a>
                </div>

            </div>
        </section>

        {{-- MAIN CONTENT --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white relative">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center md:justify-between gap-12">
                <!-- Left Text -->
                <div class="md:w-1/2">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        About S2U
                    </h1>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        S2U is the premier platform connecting UPSI students with peer-to-peer services. Find tutoring,
                        design help, coding assistance, and more from your fellow students.
                    </p>
                    @auth
                        <a href="{{ route('search.index') }}"
                            class="bg-custom-teal text-white px-6 py-3 rounded-lg hover:bg-[#2c5a6a] transition-colors duration-200 font-semibold">
                            Find Your Next Service!
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="bg-custom-teal text-white px-6 py-3 rounded-lg hover:bg-[#2c5a6a] transition-colors duration-200 font-semibold">
                            Join S2U Today!
                        </a>
                    @endauth
                </div>

                <!-- Right Image -->
                <div class="md:w-1/2 flex justify-center">
                    <div
                        class="w-72 h-72 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 font-semibold text-lg">
                        Student Image Here
                    </div>
                </div>
            </div>
            <!-- Wave SVG -->
            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
                <svg class="relative block w-full h-20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                    viewBox="0 0 1200 120">
                    <path d="M0,0 C600,120 600,0 1200,120 L1200,0 L0,0 Z" class="fill-current text-custom-teal"></path>
                </svg>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-custom-teal ">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-white-100 mb-6 text-center">
                    How S2U Works
                </h1>
                <br>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <div class="mb-6">
                            <svg class="w-16 h-16 mx-auto text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Explore Verified Service Listings</h3>
                        <p class="text-gray-600 leading-relaxed">
                            All of S2U's services are thoroughly screened to ensure they are legitimate. When you log
                            in, you'll only find high-quality opportunities from verified UPSI students—no scams, junk
                            listings, or unreliable providers.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <div class="mb-6">
                            <svg class="w-16 h-16 mx-auto text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Search Smarter, Connect Faster</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Use advanced search filters to find services that match your needs, schedule, and budget.
                            Save searches, track requests, and follow providers to stay updated on new services.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <div class="mb-6">
                            <svg class="w-16 h-16 mx-auto text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Access Student Support & Resources</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Get guidance with tutorials, guides, articles, and more, all designed to help you get the
                            most out of our platform and succeed academically.
                        </p>
                    </div>
                </div>
            </div>

        </section>

        <!-- Benefits Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">
                    Benefit of using Us
                </h1>
                <br><br>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-box">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">High-Quality Student Services</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    We help students find professional peer-to-peer services in 50+ categories, from
                                    academic tutoring to creative design, coding to language help, all within the UPSI
                                    community.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="feature-box">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Every Service & Provider Verified for
                                    You</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    Our expert team verifies and screens the best service providers and provides
                                    information on each student to help you decide whether to connect.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="feature-box">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">High-Quality Support & Resources</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    When it comes to S2U's services, we offer great resources to provide support,
                                    guidance, and tools so you can find the right service and succeed academically, and
                                    that includes student support you can talk to.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="feature-box">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No-Risk Satisfaction Guarantee</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    We want our users to be happy with our service. Student-friendly rates with
                                    transparent pricing. No hidden fees or commissions. It's that easy.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section id="categories" class="py-16 bg-gray-50 relative ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">
                    What We Offer
                </h1>

                <br><br>
                <!-- Left Button -->
                <button id="scrollLeft"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-custom-teal text-white p-4 rounded-full shadow-lg z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <!-- Scrollable container -->
                <div id="scrollContainer" class="flex gap-8 pb-4 overflow-x-auto cursor-pointer">
                    @foreach ($categories ?? [] as $category)
                        <div class="relative flex-none w-64 p-6 rounded-xl cursor-pointer shadow-lg transform hover:scale-105 transition-all duration-300"
                            style="background-color: white; border: 2px solid {{ $category->color }}; display: flex; flex-direction: column; justify-content: center; align-items: center;">

                            <!-- Icon -->
                            <div class="mb-4 w-20 h-20 flex items-center justify-center rounded-full bg-white group-hover:bg-transparent transition-all duration-300"
                                style="border: 2px solid {{ $category->color }};">
                                <img src="{{ asset('images/' . $category->image_path) }}"
                                    alt="{{ $category->name }}"
                                    class="w-12 h-12 object-contain group-hover:text-white"
                                    style="color: {{ $category->color }};">
                            </div>

                            <!-- Title -->
                            <div
                                class="text-xl font-semibold text-gray-900 group-hover:text-white transition duration-300 text-center">
                                {{ $category->name }}
                            </div>

                            <!-- Description -->
                            <p
                                class="text-sm text-gray-600 mt-3 group-hover:text-white transition duration-300 text-center">
                                {{ $category->description }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- Right Button -->
                <button id="scrollRight"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-custom-teal text-white p-4 rounded-full shadow-lg z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </button>
            </div>
        </section>

        <script>
            const scrollContainer = document.getElementById('scrollContainer');
            const scrollLeftButton = document.getElementById('scrollLeft');
            const scrollRightButton = document.getElementById('scrollRight');

            // Scroll by a fixed amount when buttons are clicked
            const scrollAmount = 300; // Adjust this value for scroll speed

            // Scroll left
            scrollLeftButton.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Scroll right
            scrollRightButton.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        </script>


        <!-- Step-by-Step Guide -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-2">
                    Simple Steps to Get Started
                </h2>
                <p class="text-gray-600 text-center mb-12">
                    Connect with your peers in just a few easy steps
                </p>

                <!-- Tab Buttons -->
                <div class="tab-buttons mb-12 justify-center flex flex-wrap gap-4">
                    <button @click="activeTab = 'seekers'" :class="activeTab === 'seekers' ? 'active' : ''"
                        class="tab-btn">
                        For Service Seekers
                    </button>
                    <button @click="activeTab = 'providers'" :class="activeTab === 'providers' ? 'active' : ''"
                        class="tab-btn">
                        For Service Providers
                    </button>
                </div>

                <!-- Tabs Content -->
                <div x-show="activeTab === 'seekers'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">1</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Search & Browse</h3>
                        <p class="text-gray-600">
                            Use our smart filters to find exactly what you need - tutoring, design, coding, and more.
                        </p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">2</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Connect & Chat</h3>
                        <p class="text-gray-600">
                            Send a chat request to discuss your needs, timeline, and pricing with the service provider.
                        </p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">3</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Learn & Review</h3>
                        <p class="text-gray-600">
                            Get the help you need and leave a review to help other students make informed decisions.
                        </p>
                    </div>
                </div>

                <div x-show="activeTab === 'providers'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">1</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Create Your Profile</h3>
                        <p class="text-gray-600">
                            Set up your services, showcase your skills, and set your availability status.
                        </p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">2</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Receive Requests</h3>
                        <p class="text-gray-600">
                            Get notified when students are interested in your services and start conversations.
                        </p>
                    </div>
                    <div class="text-center p-6 bg-gray-50 rounded-lg shadow-sm card-hover">
                        <div class="step-number">3</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Earn & Grow</h3>
                        <p class="text-gray-600">
                            Help fellow students while earning money and building your reputation in the community.
                        </p>
                    </div>
                </div>
            </div>
        </section>








        <!-- Footer -->
        @include('layouts.footer')

    </div>
</body>

</html>
