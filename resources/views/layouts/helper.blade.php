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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    @include('layouts.navbar')

        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
