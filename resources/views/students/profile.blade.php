<x-app-layout>



    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <div class="mb-8 text-center mt-10">
             
            </div>

            {{-- MAIN --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <!-- About Section -->
                        @if ($user->bio)
                            <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-start">
                                <!-- Profile Picture -->
                                <div
                                    class="w-56 h-56 rounded-full overflow-hidden border-4 border-gray-300 mr-7 flex-shrink-0">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('images/profile/' . $user->profile_photo_path) }}"
                                            alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-4xl">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Bio Text -->
                                <div class="flex flex-col gap-2">
                                    <!-- Name + Verified + Availability -->
                                    <div class="flex items-center gap-2">
                                        <h2 class="font-bold text-[25px] text-[#252525]">{{ $user->name }}</h2>

                                        @if ($user->trust_badge)
                                            <span class="inline-flex items-center text-blue-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif

                                        @if ($user->is_available)
                                            <span
                                                class="inline-flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 
                        px-2.5 py-1 rounded-full text-xs font-medium mt-1">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                Available
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 
                        px-2.5 py-1 rounded-full text-xs font-medium mt-1">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                Unavailable
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Rating (besar & center) -->
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-6 h-6 {{ $i <= ($user->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endfor
                                        <span class="text-lg font-semibold text-gray-900 ml-2">
                                            {{ number_format($user->average_rating ?? 0, 1) }}
                                        </span>
                                    </div>

                                    <!-- Email -->
                                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>

                                    <!-- Faculty / Fakulti -->
                                    @if ($user->faculty)
                                        <p class="text-gray-700 font-medium text-sm">{{ $user->faculty }}</p>
                                    @endif

                                    <!-- Bio -->
                                    <p class="text-gray-600 leading-relaxed">{{ $user->bio }}</p>
                                </div>

                            </div>

                        @endif

                        @if ($user->isStudent() && ($user->faculty || $user->course))
                            <div class="bg-white rounded-xl border border-gray-200 p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Academic Info</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Faculty</p>
                                        <p class="text-gray-900 font-medium">{{ $user->faculty ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Course/Program</p>
                                        <p class="text-gray-900 font-medium">{{ $user->course ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <!-- Services Section (only for student providers) -->
                        @if ($user->isStudent())
                            <div class="bg-white rounded-xl border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-semibold text-gray-900">Services Offered</h2>
                                    <span class="text-sm text-gray-500">{{ $servicesActiveCount }}
                                        {{ Str::plural('service', $servicesActiveCount) }}</span>
                                </div>

                                @if ($servicesActiveCount > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach ($services->take(6) as $service)
                                            <div
                                                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all flex flex-col overflow-hidden">

                                                <!-- Service Image -->
                                                <a href="{{ route('student-services.show', $service) }}"
                                                    class="relative block h-40 bg-gray-100 overflow-hidden group">
                                                    @if ($service->image_path)
                                                        <img src="{{ asset('images/' . $service->image_path) }}"
                                                            alt="{{ $service->title }}"
                                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                                            No Image</div>
                                                    @endif
                                                </a>

                                                <!-- Content -->
                                                <div class="p-4 flex flex-col flex-grow">
                                                    <!-- Service Title -->
                                                    <a href="{{ route('student-services.show', $service) }}"
                                                        class="text-lg font-semibold text-gray-900 hover:text-indigo-600 line-clamp-2 block">
                                                        {{ Str::limit($service->title, 50) }}
                                                    </a>

                                                    <!-- Description -->
                                                    <p class="line-clamp-2 text-sm text-gray-600 mb-2"
                                                        style="font-size: 14px; color:#484745;">
                                                        {{ Str::limit($service->description, 70) }}
                                                    </p>

                                                    <!-- Price -->
                                                    @if ($service->suggested_price)
                                                        <div class="text-gray-900 mb-2" style="font-size: 16px;">
                                                            From
                                                            <strong>RM{{ number_format($service->suggested_price, 2) }}</strong>
                                                        </div>
                                                    @endif

                                                    <!-- View Details Button -->
                                                    <a href="{{ route('student-services.show', $service) }}"
                                                        class="mt-3 inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-900 hover:bg-gray-100 text-gray-900 text-base font-medium rounded shadow transition duration-200">
                                                        View Details
                                                    </a>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">No services yet</h3>
                                        <p class="mt-2 text-gray-500">This student hasn't added any services yet.</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Reviews Section -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Reviews & Feedback</h2>
                                <span class="text-sm text-gray-500">{{ $user->reviews_received_count ?? 0 }}
                                    {{ Str::plural('review', $user->reviews_received_count ?? 0) }}</span>
                            </div>

                            @if (isset($reviews) && $reviews->count() > 0)
                                <div class="space-y-6">
                                    @foreach ($reviews as $review)
                                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                            <div class="flex items-start space-x-4">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <a href="{{ route('students.profile', $review->reviewer) }}"
                                                            class="font-medium text-upsi-blue hover:text-upsi-blue/80">
                                                            {{ $review->reviewer->name }}
                                                        </a>
                                                        <div class="flex items-center">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 000.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                    </path>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    @if ($review->comment)
                                                        <p class="text-gray-600 mb-2">{{ $review->comment }}</p>
                                                    @endif
                                                    <p class="text-sm text-gray-500">
                                                        {{ $review->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($reviews->count() >= 10)
                                    <div class="mt-6 text-center">
                                        <a href="{{ route('students.profile', $user->id) }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                            Load More Reviews
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l2.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                                    <p class="mt-2 text-gray-500">Be the first to leave a review for
                                        {{ $user->name }}!</p>
                                </div>
                            @endif
                        </div>

                        <!-- Skills & Expertise -->
                        @if ($user->skills)
                            <div class="bg-white rounded-xl border border-gray-200 p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Skills & Expertise</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $user->skills) as $skill)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                            {{ trim($skill) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Student Profile Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-8">
                            <!-- Profile Header -->
                            <div class="px-6 py-8 text-left">
                                <div class="mx-auto mb-4 flex items-center justify-left space-x-3">
                                    <!-- Profile Image -->
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('images/profile/' . $user->profile_photo_path) }}"
                                            alt="{{ $user->name }}"
                                            class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-xl">
                                    @else
                                        <div
                                            class="w-16 h-16 bg-black rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif

                                    <!-- User Name -->
                                    <h1 class="text-xl font-bold text-black">{{ $user->name }}</h1>
                                </div>
                            </div>


                            <!-- Contact Actions -->
                            <div class="p-6 space-y-3">
                                @auth
                                    @php $viewer = auth()->user(); @endphp
                                    @if ($viewer->id !== $user->id && $viewer->isCommunity() && $user->isStudent())
                                        <a href="{{ route('chat.request', ['user' => $user->id]) }}"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-black hover:bg-gray-800 text-white text-base font-medium rounded-md shadow transition duration-200">
                                            Contact me
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-black hover:bg-gray-800 text-white text-base font-medium rounded shadow transition duration-200">
                                        Login to Contact
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</x-app-layout>
