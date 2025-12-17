<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Favourites - S2U</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        h1,
        h2,
        h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .service-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-removed {
            transform: scale(0.9);
            opacity: 0;
        }
    </style>
</head>

<body class="antialiased text-slate-800">

    @include('layouts.navbar')

    <main class="max-w-7xl mx-auto px-6 pt-32 pb-20">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">My Saved Services</h1>
                <p class="text-slate-500 mt-2 text-lg">Manage and view the student helpers you've bookmarked.</p>
            </div>
            <a href="{{ route('services.index') }}"
                class="text-indigo-600 font-bold hover:text-indigo-700 flex items-center gap-2 transition-all">
                Find more helpers <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>
        </div>

        @if ($favourites->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($favourites as $service)
                    <div
                        class="service-card group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl overflow-hidden relative flex flex-col">

                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/400x300' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <div class="absolute bottom-4 left-4">
                                <span
                                    class="bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider">
                                    {{ is_object($service->category) ? $service->category->name : $service->category['name'] ?? 'Service' }}
                                </span>
                            </div>

                            <button onclick="confirmRemove({{ $service->id }}, this)"
                                class="absolute top-4 right-4 bg-white/95 text-red-500 w-10 h-10 rounded-full flex items-center justify-center shadow-md hover:bg-red-500 hover:text-white transition-all transform active:scale-90">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-1 text-amber-400 mb-3">
                                <i class="fa-solid fa-star text-xs"></i>
                                <i class="fa-solid fa-star text-xs"></i>
                                <i class="fa-solid fa-star text-xs"></i>
                                <i class="fa-solid fa-star text-xs"></i>
                                <i class="fa-solid fa-star text-xs"></i>
                                <span class="text-slate-400 text-xs font-medium ml-1">(New)</span>
                            </div>

                            <h3 class="font-bold text-xl text-slate-900 leading-tight mb-2">
                                <a href="{{ route('services.details', $service->id) }}"
                                    class="hover:text-indigo-600 transition-colors">
                                    {{ $service->title }}
                                </a>
                            </h3>

                            <p class="text-slate-500 text-sm line-clamp-2 mb-6">
                                {{ Str::limit(strip_tags($service->description), 80) }}
                            </p>

                            <div class="mt-auto pt-5 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-100">
                                        {{ substr($service->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $service->user->name }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Starts at
                                    </p>
                                    <p class="text-lg font-extrabold text-indigo-600">
                                        RM{{ number_format($service->basic_price ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $favourites->links() }}
            </div>
        @else
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
                <div
                    class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fa-regular fa-heart text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Your wishlist is empty</h3>
                <p class="text-slate-500 mt-2 mb-8">Save services you're interested in to see them here.</p>
                <a href="{{ route('services.index') }}"
                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                    Explore Services
                </a>
            </div>
        @endif
    </main>

    <script>
        function confirmRemove(serviceId, btn) {
            Swal.fire({
                title: 'Remove from favourites?',
                text: "You can always add this service back later.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Red
                cancelButtonColor: '#64748b', // Slate
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                borderRadius: '1.5rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    performRemove(serviceId, btn);
                }
            });
        }

        function performRemove(serviceId, btn) {
            // Add loading state to button
            btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';
            btn.disabled = true;

            fetch("{{ route('favorites.services.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        service_id: serviceId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const card = btn.closest('.service-card');
                        card.classList.add('card-removed');

                        // Success Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Service removed'
                        });

                        setTimeout(() => {
                            card.remove();
                            if (document.querySelectorAll('.service-card').length === 0) {
                                location.reload();
                            }
                        }, 400);
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                    btn.innerHTML = '<i class="fa-solid fa-heart"></i>';
                    btn.disabled = false;
                });
        }
    </script>

</body>

</html>
