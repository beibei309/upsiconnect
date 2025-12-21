<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <br><br>
            {{-- 1. PROFILE HEADER SECTION --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
                {{-- Banner --}}
                <div class="h-40 bg-gradient-to-r from-indigo-900 to-slate-900 relative">
                    <div class="absolute inset-0 bg-white/5 pattern-dots"></div>
                </div>
                
                <div class="px-8 pb-8">
                    <div class="relative flex flex-col md:flex-row items-end -mt-16 mb-6">
                        {{-- Avatar --}}
                        <div class="relative group">
                            <div class="w-32 h-32 md:w-44 md:h-44 rounded-full border-4 border-white overflow-hidden bg-white shadow-lg">
                                @if ($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                        alt="{{ $user->name }}" class="w-full h-full object-cover transition transform group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-white font-bold text-5xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Verified Badge --}}
                            @if ($user->trust_badge)
                                <div class="absolute bottom-3 right-3 bg-blue-500 text-white w-8 h-8 flex items-center justify-center rounded-full border-2 border-white shadow-sm"
                                    title="Verified Student">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Name & Status --}}
                        <div class="md:ml-8 mt-6 md:mt-0 flex-1 w-full">
                            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 leading-tight mb-1">{{ $user->name }}</h1>
                                    <div class="flex items-center gap-2 text-gray-600 font-medium mb-3">
                                        <i class="fa-solid fa-graduation-cap text-indigo-500"></i>
                                        {{ $user->faculty ?? 'Faculty of Computing' }}
                                        <span class="text-gray-300 mx-1">•</span>
                                        <span class="text-sm text-gray-500">{{ $user->course ?? 'Student' }}</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-4">
                                        {{-- Availability Badge --}}
                                        @if ($user->is_available)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                                Available for hire
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                <span class="h-2 w-2 rounded-full bg-slate-400"></span> Busy / Unavailable
                                            </span>
                                        @endif

                                        {{-- Rating Badge --}}
                                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-bold shadow-sm">
                                            <i class="fa-solid fa-star text-yellow-500"></i>
                                            {{ number_format($user->average_rating ?? 0, 1) }}
                                            <span class="font-normal text-yellow-600 opacity-80">({{ $user->reviews_received_count ?? 0 }} reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="mt-6 md:mt-0 flex flex-col sm:flex-row gap-3">
                                    @auth
                                        @if (auth()->id() !== $user->id)
                                            @php
                                                // Prepare WhatsApp Link
                                                $rawPhone = $user->phone_number ?? ($user->phone ?? '');
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                                if (substr($cleanPhone, 0, 1) === '0') {
                                                    $cleanPhone = '60' . substr($cleanPhone, 1);
                                                }
                                                $whatsappUrl = "https://wa.me/{$cleanPhone}?text=Hi " . urlencode($user->name) . ", I saw your profile on S2U.";
                                            @endphp

                                            @if(!empty($cleanPhone))
                                                <a href="{{ $whatsappUrl }}" target="_blank"
                                                    class="inline-flex items-center justify-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition shadow-lg shadow-green-500/30 transform hover:-translate-y-0.5">
                                                    <i class="fa-brands fa-whatsapp text-xl mr-2"></i>
                                                    Chat on WhatsApp
                                                </a>
                                            @else
                                                <button disabled class="inline-flex items-center justify-center px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                                                    No Phone Linked
                                                </button>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transition">
                                            Login to Contact
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bio Section --}}
                    @if ($user->bio)
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">About Me</h3>
                            <p class="text-gray-700 leading-relaxed text-lg max-w-4xl font-light">"{{ $user->bio }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 2. CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT SIDEBAR (Info, Skills, Education, Exp) --}}
                <div class="space-y-8">
                    {{-- Stats Card --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                <span class="text-gray-500">Member Since</span>
                                <span class="font-semibold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                <span class="text-gray-500">Total Services</span>
                                <span class="font-semibold text-gray-900">{{ $services->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Responds in</span>
                                <span class="font-semibold text-gray-900">~ 1 Hour</span>
                            </div>
                        </div>
                    </div>

                    @if ($user->skills)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-wand-magic-sparkles text-indigo-500"></i> Skills
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $user->skills) as $skill)
                                    <span class="px-3 py-1 rounded-lg text-sm font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($user->faculty || $user->course)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-university text-indigo-500"></i> Education
                            </h3>
                            <div class="space-y-4">
                                <div class="relative pl-4 border-l-2 border-indigo-200">
                                    <h4 class="font-semibold text-gray-900">{{ $user->course ?? 'Course Not Set' }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->faculty ?? 'Faculty Not Set' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Current Student</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Resume / Experience Section --}}
                    @if ($user->work_experience_message || $user->work_experience_file)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-indigo-500"></i> Experience
                            </h3>
                            
                            @if ($user->work_experience_message)
                                <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100 mb-4 italic">
                                    "{!! nl2br(e($user->work_experience_message)) !!}"
                                </div>
                            @endif

                            @if ($user->work_experience_file)
                                <a href="{{ asset('storage/' . $user->work_experience_file) }}" target="_blank"
                                   class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm group">
                                    <i class="fa-regular fa-file-pdf text-red-500 mr-2 group-hover:scale-110 transition-transform"></i>
                                    View Resume / CV
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- RIGHT CONTENT (Services & Reviews) --}}
                <div class="lg:col-span-2 space-y-10">

                    {{-- 🟢 SERVICES OFFERED SECTION --}}
                    <div id="services-section">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-slate-900">Services Offered</h2>
                            <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm shadow-indigo-200">
                                {{ $services->count() }} Active
                            </span>
                        </div>

                        @if ($services->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($services as $service)
                                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all group overflow-hidden flex flex-col h-full hover:-translate-y-1 duration-300">
                                        
                                        {{-- Service Image --}}
                                        <a href="{{ route('student-services.show', $service) }}" class="block h-48 bg-gray-100 overflow-hidden relative">
                                            @if ($service->image_path)
                                                <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300">
                                                    <i class="fa-regular fa-image text-4xl"></i>
                                                </div>
                                            @endif
                                            
                                            {{-- Price Tag --}}
                                            <div class="absolute bottom-3 right-3 bg-slate-900/90 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
                                                RM {{ number_format($service->basic_price ?? 0, 0) }}
                                            </div>
                                        </a>

                                        {{-- Service Details --}}
                                        <div class="p-5 flex flex-col flex-1">
                                            <div class="mb-2">
                                                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                                    {{ $service->category->name ?? 'Service' }}
                                                </span>
                                            </div>
                                            
                                            <a href="{{ route('student-services.show', $service) }}" class="text-lg font-bold text-gray-900 hover:text-indigo-600 line-clamp-2 leading-tight mb-2">
                                                {{ $service->title }}
                                            </a>
                                            
                                            <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">
                                                {{ Str::limit($service->description, 80) }}
                                            </p>
                                            
                                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                                <div class="flex items-center gap-1 text-xs text-gray-500 font-medium">
                                                    <i class="fa-solid fa-star text-yellow-400"></i> {{ number_format($service->average_rating ?? 5.0, 1) }}
                                                </div>
                                                <a href="{{ route('student-services.show', $service) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group-hover:gap-2 transition-all">
                                                    View Details <i class="fa-solid fa-arrow-right text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Empty Services State --}}
                            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                    <i class="fa-solid fa-box-open text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No services listed yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- 🟢 REVIEWS SECTION --}}
                    <div id="reviews-section" class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                        {{-- Review Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                            <h2 class="text-xl font-bold text-gray-900">Reviews & Ratings</h2>
                            
                            <div class="flex items-center gap-3 bg-yellow-50 px-5 py-3 rounded-xl border border-yellow-100">
                                <span class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($user->average_rating ?? 0, 1) }}</span>
                                <div class="flex flex-col">
                                    <div class="flex text-yellow-400 text-xs mb-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star {{ $i < round($user->average_rating ?? 0) ? '' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wide">{{ $user->reviews_received_count ?? 0 }} Total Reviews</span>
                                </div>
                            </div>
                        </div>

                        {{-- Reviews List --}}
                        @if (isset($reviews) && $reviews->count() > 0)
                            <div class="space-y-8">
                                @foreach ($reviews as $review)
                                    <div class="border-b border-gray-50 pb-8 last:border-0 last:pb-0">
                                        <div class="flex items-start gap-4">
                                            {{-- Reviewer Avatar --}}
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-white flex items-center justify-center text-indigo-600 font-bold border border-indigo-50 shadow-sm">
                                                    {{ substr($review->reviewer->name ?? 'A', 0, 1) }}
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-sm">{{ $review->reviewer->name ?? 'Anonymous' }}</h4>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <div class="flex text-yellow-400 text-xs">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                                                                @endfor
                                                            </div>
                                                            <span class="text-xs text-gray-400">• {{ $review->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- Context Badge: Which Service? --}}
                                                @if($review->service)
                                                    <div class="mt-2 mb-3">
                                                        <a href="{{ route('student-services.show', $review->service->id) }}" 
                                                           class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors group">
                                                            <svg class="w-3 h-3 mr-1.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                            </svg>
                                                            Service: {{ $review->service->title }}
                                                        </a>
                                                    </div>
                                                @endif

                                                @if ($review->comment)
                                                    <div class="bg-gray-50 p-4 rounded-xl rounded-tl-none text-sm text-gray-700 leading-relaxed relative border border-gray-100">
                                                        "{{ $review->comment }}"
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Empty Reviews State --}}
                            <div class="text-center py-10">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 text-gray-300 mb-4">
                                    <i class="fa-regular fa-comment-dots text-2xl"></i>
                                </div>
                                <h3 class="text-gray-900 font-bold mb-1">No reviews yet</h3>
                                <p class="text-gray-500 text-sm">Be the first to hire and review {{ $user->name }}!</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>