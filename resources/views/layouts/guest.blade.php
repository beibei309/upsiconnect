<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="upsi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UpsiConnect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        .gradient-bg { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        }
        .auth-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen gradient-bg flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Header -->
        <div class="w-full max-w-md px-6 py-4">
            <a href="{{ url('/') }}" class="flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-white mb-2">UpsiConnect</h1>
                    <p class="text-indigo-100 text-sm">Connect. Learn. Grow Together.</p>
                </div>
            </a>
        </div>

        <!-- Main Content -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 auth-card shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-indigo-100 text-sm">
                Built with ❤️ for UPSI students
            </p>
            <div class="mt-2 flex items-center justify-center space-x-4 text-indigo-200 text-xs">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition">Help</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy</a>
                <span>•</span>
                <a href="{{ route('terms') }}" class="hover:text-white transition">Terms</a>
            </div>
        </div>
    </div>
</body>
</html>
