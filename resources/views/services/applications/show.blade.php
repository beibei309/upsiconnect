<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('services.applications.index') }}" class="inline-flex items-center text-upsi-blue hover:text-upsi-blue/80 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Applications
                </a>
            </div>

            <!-- Application Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-upsi-blue bg-gradient-to-r from-upsi-blue to-upsi-blue/90 px-8 py-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $application->service->title ?? $application->title }}</h1>
                            <p class="text-white/90">Applied {{ $application->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if(auth()->user()->role === 'community' && auth()->user()->id === $application->user_id && !$application->service && $application->status === 'open')
                                <a href="{{ route('services.applications.edit', $application) }}" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/20 hover:bg-white/30 text-white transition-colors">
                                    Edit Details
                                </a>
                            @endif
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $application->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <!-- Side-by-side: Service vs Request Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Information -->
                        @if($application->service)
                            <div>
                                <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Service Details</h2>
                                <div class="bg-upsi-light-gray rounded-xl p-6 space-y-6">
                                    <!-- Title -->
                                    <div>
                                        <h3 class="font-semibold text-upsi-text-primary">{{ $application->service->title }}</h3>
                                    </div>

                                    <!-- Summary cards aligned with Request Details -->
                                    @php
                                        $svc = $application->service;
                                        $svcType = $svc->category ? $svc->category->name : 'General';
                                        $svcPrice = $svc->price_range ?? ($svc->suggested_price !== null ? 'RM ' . number_format($svc->suggested_price, 2) : 'Negotiable');
                                        $svcStatus = $svc->status ?? ($svc->isAvailable() ? 'available' : 'busy');
                                        $svcStatusLabel = ucfirst(str_replace('_',' ', $svcStatus));
                                    @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Service Type</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $svcType }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4-1.343 4-3M5 19h14" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Price</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $svcPrice }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5v14" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Availability</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $svcStatusLabel }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if(!empty($application->service->description))
                                        <div>
                                            <p class="text-sm text-upsi-text-primary/60 mb-2">Description</p>
                                            <p class="text-upsi-text-primary whitespace-pre-line break-words">{{ $application->service->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Request / Application Details -->
                        <div>
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Request Details</h2>
                            <div class="bg-upsi-light-gray rounded-xl p-6 space-y-6">
                                @if(!$application->service)
                                    <!-- Original custom-request summary cards -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Service Type</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $application->formatted_service_type }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4-1.343 4-3M5 19h14" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Budget</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $application->formatted_budget }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Timeline</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $application->formatted_timeline }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <p class="text-sm text-upsi-text-primary/60 mb-2">Description</p>
                                        <p class="text-upsi-text-primary whitespace-pre-line break-words">{{ $application->description }}</p>
                                    </div>

                                    <!-- Preferred Contact Methods as chips -->
                                    @php
                                        $methodsRaw = $application->contact_methods;
                                        $methods = is_array($methodsRaw)
                                            ? $methodsRaw
                                            : (is_string($methodsRaw) ? (json_decode($methodsRaw, true) ?? []) : []);
                                    @endphp
                                    @if(!empty($methods))
                                        <div>
                                            <p class="text-sm text-upsi-text-primary/60 mb-2">Preferred Contact</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($methods as $m)
                                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-gray-200 text-upsi-text-primary text-sm">
                                                        @switch($m)
                                                            @case('platform_chat')
                                                                <svg class="w-4 h-4 text-upsi-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a4 4 0 01-4 4H7l-4 4V6a4 4 0 014-4h10a4 4 0 014 4v9z"/></svg>
                                                                @break
                                                            @case('email')
                                                                <svg class="w-4 h-4 text-upsi-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7l8 6 8-6"/></svg>
                                                                @break
                                                            @case('phone')
                                                                <svg class="w-4 h-4 text-upsi-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3l2 4-2 2a12 12 0 006 6l2-2 4 2v3a2 2 0 01-2 2h-1C9.163 20 4 14.837 4 8V7a2 2 0 01-1-2z"/></svg>
                                                                @break
                                                            @default
                                                                <svg class="w-4 h-4 text-upsi-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                                                        @endswitch
                                                        <span>{{ $m === 'platform_chat' ? 'Platform chat' : ucfirst(str_replace('_',' ', $m)) }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <!-- Application summary when linked to a service -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Status</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Applied On</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">{{ $application->created_at->format('F j, Y \a\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($application->message))
                                    <div>
                                        <p class="text-sm text-upsi-text-primary/60 mb-2">Message</p>
                                        <p class="text-upsi-text-primary">{{ $application->message }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Parties Involved -->
                    <div>
                        <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Parties Involved</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Applicant -->
                            <div class="bg-upsi-light-gray rounded-xl p-4">
                                <p class="text-sm text-upsi-text-primary/60 mb-2">Applicant</p>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('students.profile', $application->user) }}" class="block group/avatar">
                                        @if($application->user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $application->user->profile_photo_path) }}" 
                                                 alt="{{ $application->user->name }}" 
                                                 class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($application->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('students.profile', $application->user) }}" class="font-semibold text-upsi-blue hover:text-upsi-blue/80">
                                            {{ $application->user->name }}
                                        </a>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @php($applicantRating = round($application->user->average_rating ?? 0))
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $applicantRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-upsi-text-primary/60">{{ number_format($application->user->average_rating ?? 0, 1) }}</span>
                                        </div>
                                        <p class="text-xs text-upsi-text-primary/60">{{ $application->user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Provider -->
                            @if($application->service && $application->service->user)
                                <div class="bg-upsi-light-gray rounded-xl p-4">
                                    <p class="text-sm text-upsi-text-primary/60 mb-2">Service Provider</p>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('students.profile', $application->service->user) }}" class="block group/avatar">
                                            @if($application->service->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $application->service->user->profile_photo_path) }}" 
                                                     alt="{{ $application->service->user->name }}" 
                                                     class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ substr($application->service->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div>
                                            <a href="{{ route('students.profile', $application->service->user) }}" class="font-semibold text-upsi-blue hover:text-upsi-blue/80">
                                                {{ $application->service->user->name }}
                                            </a>
                                            <div class="flex items-center space-x-2">
                                                <div class="flex items-center">
                                                    @php($providerRating = round($application->service->user->average_rating ?? 0))
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $providerRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-upsi-text-primary/60">{{ number_format($application->service->user->average_rating ?? 0, 1) }}</span>
                                            </div>
                                            <p class="text-xs text-upsi-text-primary/60">{{ $application->service->user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Application Message (chat-linked applications) -->
                    @if(!empty($application->message))
                        <div>
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Application Message</h2>
                            <div class="bg-upsi-light-gray rounded-xl p-6">
                                <p class="text-upsi-text-primary">{{ $application->message }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    @if($application->service && $application->status === 'open' && auth()->user()->role === 'student' && auth()->user()->id === $application->service->user_id)
                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Actions</h2>
                            <div class="flex items-center space-x-4">
                                <button onclick="acceptApplication()" 
                                        class="px-6 py-3 bg-upsi-blue text-white font-semibold rounded-xl hover:bg-upsi-blue/90 transition-all duration-200">
                                    Accept Application
                                </button>
                                <button onclick="rejectApplication()" 
                                        class="px-6 py-3 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all duration-200">
                                    Decline Application
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($application->status === 'in_progress')
                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Mark as Completed</h2>
                            <p class="text-sm text-upsi-text-primary/60 mb-4">
                                Once you've completed this service, mark it as completed. 
                                @if(auth()->user()->id === $application->user_id)
                                    After both parties confirm completion, you can leave a review.
                                @else
                                    The applicant will also need to confirm completion.
                                @endif
                            </p>
                            <button onclick="markCompleted()" 
                                    class="px-6 py-3 bg-green-50 text-green-600 font-semibold rounded-xl hover:bg-green-100 transition-all duration-200">
                                Mark as Completed
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function acceptApplication() {
            if (!confirm('Accept this application? The service will be marked as in progress.')) return;

            fetch('{{ route("services.applications.accept", $application) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to accept application');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function rejectApplication() {
            if (!confirm('Decline this application? This cannot be undone.')) return;

            fetch('{{ route("services.applications.reject", $application) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("services.applications.index") }}';
                } else {
                    alert(data.message || 'Failed to decline application');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function markCompleted() {
            if (!confirm('Mark this service as completed?')) return;

            fetch('{{ route("services.applications.mark-completed", $application) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to mark as completed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    </script>
    @endpush
</x-app-layout>
