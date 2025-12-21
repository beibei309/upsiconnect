<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <br><br>
            {{-- 1. PROFILE HEADER SECTION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="h-32 bg-gradient-to-r from-slate-800 to-slate-900"></div>
                <div class="px-8 pb-8">
                    <div class="relative flex flex-col md:flex-row items-end -mt-12 mb-4">
                        <div class="relative">
                            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white overflow-hidden bg-white shadow-md">
                                @if ($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                         alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-white font-bold text-4xl">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            @if ($user->trust_badge)
                                <div class="absolute bottom-2 right-2 bg-blue-500 text-white p-1 rounded-full border-2 border-white" title="Trusted Seller">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="md:ml-6 mt-4 md:mt-0 flex-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                    <p class="text-gray-500 font-medium">{{ $user->faculty ?? 'General User' }} {{ $user->course ? '• ' . $user->course : '' }}</p>
                                    
                                    <div class="mt-2 flex items-center gap-3">
                                        @if ($user->is_available)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Unavailable
                                            </span>
                                        @endif

                                        <div class="flex items-center gap-1 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="font-bold">{{ number_format($user->average_rating ?? 0, 1) }}</span>
                                            <span class="text-gray-400">({{ $user->reviews_received_count ?? 0 }} reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 md:mt-0">
                                    @auth
                                        @php $viewer = auth()->user(); @endphp
                                        @if ($viewer->id !== $user->id)
                                            <a href="{{ route('chat.request', ['user' => $user->id]) }}" class="inline-flex items-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition shadow-lg transform hover:-translate-y-0.5">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                Contact Me
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition shadow-lg">
                                            Login to Contact
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->bio)
                        <div class="mt-6 border-t border-gray-100 pt-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">About Me</h3>
                            <p class="text-gray-600 leading-relaxed max-w-4xl">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 2. CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT SIDEBAR (Info, Skills, Education, Exp) --}}
                <div class="space-y-8">
                    @if ($user->skills)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Skills
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
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                                Education (UPSI)
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

                    @if ($user->work_experience_message || $user->work_experience_file)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Experience & Resume
                            </h3>
                            
                            <div class="space-y-4">
                                {{-- Message --}}
                                @if ($user->work_experience_message)
                                    <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                                        {!! nl2br(e($user->work_experience_message)) !!}
                                    </div>
                                @endif

                                {{-- Resume File Download --}}
                                @if ($user->work_experience_file)
                                    <div class="pt-2">
                                        <a href="{{ asset('storage/' . $user->work_experience_file) }}" target="_blank"
                                           class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                            <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            View Resume / CV
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="text-center text-sm text-gray-400">
                        Member since {{ $user->created_at->format('F Y') }}
                    </div>
                </div>

                {{-- RIGHT CONTENT (Services & Reviews) --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    @if ($user->isStudent())
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-bold text-gray-900">Services Offered</h2>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $user->services->where('is_active', true)->count() }} Active Services
                                </span>
                            </div>

                            @if ($user->services->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @foreach ($user->services as $service)
                                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all group overflow-hidden flex flex-col h-full">
                                            <a href="{{ route('student-services.show', $service) }}" class="block h-48 bg-gray-100 overflow-hidden relative">
                                                @if ($service->image_path)
                                                    <img src="{{ asset('images/' . $service->image_path) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                                                    Starting at RM{{ number_format($service->suggested_price ?? $service->basic_price ?? 0, 0) }}
                                                </div>
                                            </a>

                                            <div class="p-5 flex flex-col flex-1">
                                                <a href="{{ route('student-services.show', $service) }}" class="text-lg font-bold text-gray-900 hover:text-indigo-600 line-clamp-2 mb-2">
                                                    {{ $service->title }}
                                                </a>
                                                <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">{{ $service->description }}</p>
                                                
                                                <div class="pt-4 border-t border-gray-50 mt-auto">
                                                    <a href="{{ route('student-services.show', $service) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                                        View Details <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                                    <p class="text-gray-500">No active services offered yet.</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Reviews History</h2>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($user->average_rating ?? 0, 1) }}</span>
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i=0; $i<5; $i++)
                                        <i class="fas fa-star {{ $i < round($user->average_rating ?? 0) ? '' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        @if (isset($reviews) && $reviews->count() > 0)
                            <div class="space-y-6">
                                @foreach ($reviews as $review)
                                    <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                                                    {{ substr($review->reviewer->name ?? 'A', 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-sm">{{ $review->reviewer->name ?? 'Anonymous' }}</h4>
                                                        <div class="flex text-yellow-400 text-xs mt-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <svg class="w-3 h-3 {{ $i <= $review->rating ? '' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                            @endfor
                                                            <span class="text-gray-400 ml-2">{{ $review->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- CONTEXT: Review for which service (UPDATED with Tag Badge) --}}
                                                @if($review->service)
                                                    <div class="mt-2 mb-1">
                                                        <a href="{{ route('student-services.show', $review->service->id) }}" 
                                                           class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors group">
                                                            <svg class="w-3 h-3 mr-1.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                            </svg>
                                                            {{ $review->service->title }}
                                                        </a>
                                                    </div>
                                                @endif

                                                @if ($review->comment)
                                                    <p class="text-gray-600 text-sm mt-2 leading-relaxed">"{{ $review->comment }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </div>
                                <h3 class="text-gray-900 font-medium">No reviews yet</h3>
                                <p class="text-gray-500 text-sm">Be the first to hire and review {{ $user->name }}!</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>