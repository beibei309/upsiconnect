<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'S2U') }} - Connect, Learn, Grow</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #6b7fd7 0%, #7c8ee0 100%);
        }

        .stats-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
        }

        .stat-item {
            margin: 1.5rem 0;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #6b7fd7;
            display: block;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .feature-box {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-4px);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: #666;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 3rem;
            line-height: 1.6;
        }

        .btn-primary {
            background: #6b7fd7;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #5a6ec6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 127, 215, 0.3);
        }

        .step-number {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: #6b7fd7;
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
            border: 2px solid #6b7fd7;
            background: white;
            color: #6b7fd7;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: #6b7fd7;
            color: white;
        }

        .hero-image-placeholder {
            background: #e5e7eb;
            border-radius: 50%;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.25rem;
            font-weight: 600;
            max-width: 400px;
            margin: 0 auto;
        }

        .story-image-placeholder {
            background: #e5e7eb;
            border-radius: 12px;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>

<body class="antialiased bg-white">
    <div x-data="{
        activeTab: 'seekers'
    }">

        <!-- Navigation -->
        @include('layouts.navbar')

        <!-- Hero Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-12">
                    <!-- Left: Text Content -->
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            About S2U
                        </h1>
                        <!--<p class="text-xl text-gray-600 leading-relaxed mb-6">
                            Since 2024, S2U has been the premier platform connecting UPSI students with peer-to-peer services.
                            With extensive experience, we specialize in helping students find tutoring, design help, coding assistance,
                            and more from their fellow students.
                        </p> -->
                        <p class="text-lg text-gray-600 leading-relaxed mb-8">
                            S2U is the premier platform connecting UPSI students with peer-to-peer services. Find
                            tutoring, design help, coding assistance, and more from your fellow students.
                        </p>
                        @auth
                            <a href="{{ route('search.index') }}" class="btn-primary">
                                Find Your Next Service!
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">
                                Join S2U Today!
                            </a>
                        @endauth
                    </div>

                    <!-- Right: Image Placeholder -->
                    <div>
                        <div class="hero-image-placeholder">
                            <span>Student Image Here</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-section">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="stat-item">
                            <span class="stat-number">1,250+</span>
                            <p class="stat-label">Students Served</p>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">340</span>
                            <p class="stat-label">Verified Services</p>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">150</span>
                            <p class="stat-label">Service Providers</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <h2 class="section-title">How S2U Works</h2>

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
                <h2 class="section-title">Benefits of Using S2U</h2>

                <div class="text-center mb-12">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary">
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">
                            Get Started
                        </a>
                    @endauth
                </div>

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

        <!-- Story Section with Image -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <h2 class="section-title">How & Why S2U Started</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left: Text Content -->
                    <div class="text-gray-600 text-lg leading-relaxed space-y-4">
                        <p>
                            S2U was created in 2024 to provide a trusted, more effective, friendly, and overall better
                            way
                            to find peer-to-peer services among UPSI students.
                        </p>
                        <p>
                            We were founded by students after they had been looking for reliable academic help and
                            creative
                            services. They realized that millions of others were just as frustrated by the lack of
                            trust,
                            scams, and inefficiencies when searching for help, so they decided to create the solution
                            everyone
                            was looking for.
                        </p>
                        <p>
                            It wasn't our original intention, but somehow S2U has become a leader in the student
                            services
                            movement that's currently transforming how UPSI students connect and help each other. We
                            continue
                            to grow as students turn to us for reliable peer-to-peer services.
                        </p>
                    </div>

                    <!-- Right: Image Placeholder -->
                    <div>
                        <img src="{{ !empty($imagePath) ? asset('images/' . $imagePath) : asset('images/service_tutor.jpg') }}"
                            alt="Students Collaborating" class="w-full h-auto rounded-lg border">
                    </div>

                </div>
            </div>
        </section>

        <!-- Step-by-Step Guide -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="section-title">Simple Steps to Get Started</h2>
                <p class="section-subtitle">
                    Connect with your peers in just a few easy steps
                </p>

                <!-- Tab Buttons -->
                <div class="tab-buttons">
                    <button @click="activeTab = 'seekers'" :class="activeTab === 'seekers' ? 'active' : ''"
                        class="tab-btn">
                        For Service Seekers
                    </button>
                    <button @click="activeTab = 'providers'" :class="activeTab === 'providers' ? 'active' : ''"
                        class="tab-btn">
                        For Service Providers
                    </button>
                </div>

                <!-- Service Seekers Tab -->
                <div x-show="activeTab === 'seekers'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="step-number">1</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Search & Browse</h3>
                        <p class="text-gray-600">
                            Use our smart filters to find exactly what you need - tutoring, design, coding, and more.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="step-number">2</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Connect & Chat</h3>
                        <p class="text-gray-600">
                            Send a chat request to discuss your needs, timeline, and pricing with the service provider.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="step-number">3</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Learn & Review</h3>
                        <p class="text-gray-600">
                            Get the help you need and leave a review to help other students make informed decisions.
                        </p>
                    </div>
                </div>

                <!-- Service Providers Tab -->
                <div x-show="activeTab === 'providers'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="step-number">1</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Create Your Profile</h3>
                        <p class="text-gray-600">
                            Set up your services, showcase your skills, and set your availability status.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="step-number">2</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Receive Requests</h3>
                        <p class="text-gray-600">
                            Get notified when students are interested in your services and start conversations.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="step-number">3</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Earn & Grow</h3>
                        <p class="text-gray-600">
                            Help fellow students while earning money and building your reputation in the community.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 gradient-bg">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Over 1,250 Students Have Used S2U to Find a Better Way to Learn
                </h2>
                @auth
                    <a href="{{ route('search.index') }}"
                        class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition inline-block">
                        Find Your Next Service!
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transition inline-block">
                        Find Your Next Service!
                    </a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
        @include('layouts.footer')

    </div>
</body>

</html>
