<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S2U - Student to Community</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* Slate-50 */
        }

        h1,
        h2,
        h3,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar for cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .rich-text ul {
            list-style-type: disc;
            padding-left: 1.25rem;
        }

        .rich-text ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
        }
    </style>
</head>

<body class="antialiased text-slate-800">
    <div x-data="{
        mobileMenuOpen: false,
        stats: { students: 1250, services: 340, reviews: 890 },
        animateStats: false
    }" x-init="setTimeout(() => animateStats = true, 1000)">

        {{-- Navigation bar --}}
        @include('layouts.navbar')

        <div class="bg-white border-b border-gray-200 pt-24 pb-12">
            <div class="max-w-7xl mx-auto px-6">
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-house mr-2"></i> Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                                <span class="font-medium text-gray-800">Find Services</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">Find Student
                            Services</h1>
                        <p class="text-slate-500 text-lg">Browse services offered by UPSI students. Fast, reliable, and
                            affordable.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-10 ">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 ">

                {{-- ======================= --}}
                {{--       SIDEBAR           --}}
                {{-- ======================= --}}
                <aside class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                        <h3 class="font-bold text-slate-900 mb-4 px-2">Categories</h3>

                        <div class="space-y-1">
                            {{-- All Categories Link (Stays Indigo) --}}
                            <a href="{{ route('services.index') }}"
                                class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
               {{ !$category_id ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg 
                    {{ !$category_id ? 'bg-white text-indigo-600 shadow-sm' : 'bg-gray-100 text-gray-500 group-hover:bg-white group-hover:shadow-sm' }}">
                                    <i class="fas fa-th-large"></i>
                                </span>
                                All Categories
                            </a>

                            @foreach ($categories as $cat)
                                <a href="?category_id={{ $cat->id }}"
                                    class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
                   {{ $category_id == $cat->id ? 'bg-indigo-50 text-slate-900' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">

                                    {{-- 👇 PERBAIKAN: Icon Container dengan Warna Kategori --}}
                                    <span class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors"
                                        {{-- Set background color to category color with 15% opacity --}}
                                        style="background-color: {{ $cat->color }}66; 
                                border: 1px solid {{ $cat->color }}40;">

                                        @if ($cat->image_path)
                                            {{-- Image Path: Icon should use its category color --}}
                                            <img src="{{ asset('images/' . $cat->image_path) }}"
                                                alt="{{ $cat->name }}" class="w-5 h-5 object-contain">
                                        @else
                                            {{-- Fallback: Use a colored circle --}}
                                            <div class="w-3 h-3 rounded-full"
                                                style="background-color: {{ $cat->color ?? '#cbd5e1' }}"></div>
                                        @endif
                                    </span>

                                    <span
                                        class="transition-colors 
                        {{ $category_id == $cat->id ? 'font-bold' : 'group-hover:text-slate-900' }}"
                                        style="{{ $category_id == $cat->id ? 'color: ' . $cat->color : '' }}">
                                        {{ $cat->name }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                {{-- ======================= --}}
                {{--      MAIN CONTENT       --}}
                {{-- ======================= --}}
                <main class="lg:col-span-9">

                    {{-- Top Filters --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-8 sticky top-2 z-10">
                        <form method="GET" class="flex flex-col md:flex-row gap-2">
                            <input type="hidden" name="category_id" value="{{ $category_id }}">

                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Search for services (e.g., Cleaning, Tutoring)..."
                                    class="block w-full pl-10 pr-3 py-3 border-none rounded-xl text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                            </div>

                            <div class="flex gap-2">
                                <div class="relative min-w-[140px]">
                                    <select name="sort" onchange="this.form.submit()"
                                        class="block w-full pl-3 pr-10 py-3 border-none rounded-xl text-gray-700 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm cursor-pointer appearance-none font-medium">
                                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest
                                        </option>
                                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest
                                        </option>
                                        <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price:
                                            Low to High</option>
                                        <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price:
                                            High to Low</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>

                                <div class="relative min-w-[130px]">
                                    <select name="available_only" onchange="this.form.submit()"
                                        class="block w-full pl-3 pr-10 py-3 border-none rounded-xl text-gray-700 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm cursor-pointer appearance-none font-medium">
                                        <option value="">Status: All</option>
                                        <option value="1"
                                            {{ request('available_only') == '1' ? 'selected' : '' }}>Available Only
                                        </option>
                                        <option value="0"
                                            {{ request('available_only') == '0' ? 'selected' : '' }}>Busy / Away
                                        </option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="hidden md:block bg-indigo-600 hover:bg-indigo-700 text-white px-5 rounded-xl transition shadow-md hover:shadow-lg">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-6">
                        @foreach ($services as $service)
                            <div
                                class="group bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden">

                                <div class="flex flex-col sm:flex-row gap-6">
                                    {{-- IMAGE SECTION --}}
                                    <div
                                        class="sm:w-64 h-56 sm:h-auto flex-shrink-0 relative rounded-xl overflow-hidden bg-gray-100">
                                        <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/1200x700' }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        @if ($service->category)
                                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold shadow-sm"
                                                style="color: {{ $service->category->color }}">
                                                {{ $service->category->name }}
                                            </div>
                                        @endif

                                        @if ($service->basic_price)
                                            <div
                                                class="sm:hidden absolute bottom-3 right-3 bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-md">
                                                RM{{ number_format($service->basic_price, 0) }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- CONTENT SECTION --}}
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-xs font-medium text-gray-400 flex items-center gap-1">
                                                        <i class="far fa-clock"></i>
                                                        {{ $service->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                                                    <span
                                                        class="text-xs font-medium {{ $service->user->is_available ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-0.5 rounded-full border {{ $service->user->is_available ? 'border-green-100' : 'border-red-100' }}">
                                                        {{ $service->user->is_available ? 'Available' : 'Busy' }}
                                                    </span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <button type="button"
                                                        onclick="handleFavourite({{ $service->id }}, {{ auth()->check() ? 'true' : 'false' }})"
                                                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-gray-400 hover:text-red-500"
                                                        title="Add to favourites">
                                                        <i id="heart-{{ $service->id }}"
                                                            class="{{ auth()->check() && $service->is_favourited ? 'fas text-red-500' : 'far' }} fa-heart text-lg"></i>
                                                    </button>
                                                    <button type="button" onclick="handleShare(this)"
                                                        data-url="{{ route('services.details', $service->id) }}"
                                                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-gray-400 hover:text-indigo-600"
                                                        title="Share">
                                                        <i class="fas fa-share-alt text-lg"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <h2
                                                class="text-xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">
                                                <a href="{{ route('services.details', $service->id) }}">
                                                    {{ $service->title }}
                                                </a>
                                            </h2>

                                            <div class="flex items-center gap-1 mb-3">
                                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                <span
                                                    class="font-bold text-slate-800 text-sm">{{ number_format($service->user->average_rating ?? 0, 1) }}</span>
                                                <span
                                                    class="text-slate-400 text-sm">({{ $service->user->reviewsReceived()->count() }}
                                                    reviews)</span>
                                            </div>

                                            <div
                                                class="rich-text text-slate-500 text-sm line-clamp-2 leading-relaxed mb-4">
                                                {!! $service->description !!}
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between border-t border-gray-100 pt-4 mt-auto">

                                            {{-- Ganti rute utama agar mengarah ke login jika guest --}}
                                            <a href="{{ Auth::guest() ? route('login') : route('students.profile', $service->user) }}"
                                                class="flex items-center gap-3 group/user" {{-- Tambahkan title untuk memberi tahu guest harus login --}}
                                                title="{{ Auth::guest() ? 'Login to view profile' : '' }}">

                                                <div class="relative">
                                                    <img src="{{ $service->user->profile_photo_path ? asset('storage/' . $service->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($service->user->name) . '&background=random' }}"
                                                        class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm group-hover/user:border-indigo-100 transition 
             
             {{-- 👇 PERBAIKAN 1: Tambahkan class blur jika pengguna adalah guest --}}
             @guest
blur-md @endguest
             ">

                                                    {{-- Optional: Overlay dan Icon Kunci jika guest (memberi tahu gambar dikunci) --}}
                                                    @guest
                                                        <div
                                                            class="absolute inset-0 flex items-center justify-center text-white bg-black/30 rounded-full">
                                                            <i class="fas fa-lock text-sm"></i>
                                                        </div>
                                                    @endguest

                                                    @if ($service->user->trust_badge)
                                                        <div class="absolute -bottom-1 -right-1 bg-blue-500 text-white w-4 h-4 rounded-full flex items-center justify-center text-[8px] border border-white"
                                                            title="Verified Helper">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-bold text-slate-800 group-hover/user:text-indigo-600 transition">
                                                        {{ Str::limit($service->user->name, 18) }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">Student Helper</span>
                                                </div>
                                            </a>

                                            <div class="flex items-center gap-4">
                                                @if ($service->basic_price)
                                                    <div class="text-right hidden sm:block">
                                                        <p class="text-xs text-slate-400 font-medium">Starting at</p>
                                                        <p class="text-lg font-bold text-indigo-600">
                                                            RM{{ number_format($service->basic_price, 2) }}</p>
                                                    </div>
                                                @endif

                                                <a href="{{ route('services.details', $service->id) }}"
                                                    class="bg-slate-900 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow hover:shadow-lg transform hover:-translate-y-0.5">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{-- {{ $services->links() }} --}}
                    </div>
                </main>
            </div>
        </div>

        <div id="shareModal"
            class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeShareModal()"></div>

            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative transform scale-95 transition-transform duration-300 mx-4">
                <button onclick="closeShareModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="text-center mb-6">
                    <div
                        class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-share-alt text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Share Service</h3>
                    <p class="text-sm text-gray-500 mt-1">Copy the link below to share this service.</p>
                </div>

                <div class="relative">
                    <input type="text" id="shareLinkInput"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl px-4 py-3 pr-24 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                        readonly>
                    <button onclick="copyShareLink()"
                        class="absolute right-1 top-1 bottom-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-lg text-sm font-medium transition">
                        Copy
                    </button>
                </div>
                <p id="copyMessage"
                    class="text-green-600 text-xs font-semibold text-center mt-3 h-4 opacity-0 transition-opacity">Link
                    Copied Successfully!</p>
            </div>
        </div>

        <script>
            // Favourite Logic
            function handleFavourite(serviceId, isLoggedIn) {
                if (!isLoggedIn) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                const heartIcon = document.getElementById('heart-' + serviceId);
                const isFavourited = heartIcon.classList.contains('fas'); // Solid icon check

                // Optimistic UI Update
                if (isFavourited) {
                    heartIcon.classList.remove('fas', 'text-red-500');
                    heartIcon.classList.add('far'); // Outline
                } else {
                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas', 'text-red-500');
                }

                fetch("{{ route('favorites.service.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        service_id: serviceId
                    })
                }).catch(err => {
                    console.error(err);
                    // Revert if error
                    if (isFavourited) {
                        heartIcon.classList.add('fas', 'text-red-500');
                        heartIcon.classList.remove('far');
                    } else {
                        heartIcon.classList.add('far');
                        heartIcon.classList.remove('fas', 'text-red-500');
                    }
                });
            }

            // Share Logic
            function handleShare(button) {
                const url = button.dataset.url;
                const modal = document.getElementById('shareModal');
                const modalContent = modal.querySelector('div.bg-white'); // Target the inner modal card

                document.getElementById('shareLinkInput').value = url; // Full URL or constructed one

                modal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }

            function closeShareModal() {
                const modal = document.getElementById('shareModal');
                const modalContent = modal.querySelector('div.bg-white');

                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    document.getElementById('copyMessage').classList.add('opacity-0');
                }, 150);
            }

            function copyShareLink() {
                const input = document.getElementById('shareLinkInput');
                input.select();
                input.setSelectionRange(0, 99999); // Mobile compatibility
                navigator.clipboard.writeText(input.value);

                const msg = document.getElementById('copyMessage');
                msg.classList.remove('opacity-0');
                setTimeout(() => msg.classList.add('opacity-0'), 2000);
            }
        </script>
    </div>
</body>

</html>
