<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('service-requests.index') }}" class="inline-flex items-center text-upsi-blue hover:text-upsi-blue/80 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Service Requests
                </a>
            </div>

            @php
                $isRequester = auth()->id() === $serviceRequest->requester_id;
                $isProvider = auth()->id() === $serviceRequest->provider_id;
            @endphp

            <!-- Request Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-upsi-blue bg-gradient-to-r from-upsi-blue to-upsi-blue/90 px-8 py-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $serviceRequest->studentService->title ?? 'Service Request' }}</h1>
                            <p class="text-white/90">Requested {{ $serviceRequest->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $serviceRequest->status_color }}">
                            {{ $serviceRequest->formatted_status }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6">
                    <!-- Two-column grid: Service vs Request -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Information -->
                        @if($serviceRequest->studentService)
                            <div>
                                <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Service Details</h2>
                                <div class="bg-upsi-light-gray rounded-xl p-6 space-y-6">
                                    @php
                                        $svc = $serviceRequest->studentService;
                                        $svcType = $svc->category ? $svc->category->name : 'General';
                                        $svcPrice = !is_null($svc->suggested_price) ? 'RM ' . number_format($svc->suggested_price, 2) : 'Negotiable';
                                        $svcStatusLabel = ($svc->user && $svc->user->is_available) ? 'available' : 'busy';
                                        $svcStatusLabel = ucfirst(str_replace('_',' ', $svcStatusLabel));
                                    @endphp
                                    <!-- Summary cards aligned with Applications view -->
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
                                                <p class="text-xs text-upsi-text-primary/60">Suggested Price</p>
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
                                    @if(!empty($svc->description))
                                        <div>
                                            <p class="text-sm text-upsi-text-primary/60 mb-2">Description</p>
                                            <p class="text-upsi-text-primary whitespace-pre-line break-words">{{ $svc->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Request Specifics -->
                        <div>
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Request Details</h2>
                            <div class="bg-upsi-light-gray rounded-xl p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @if($serviceRequest->offered_price)
                                        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.343-4 3s1.79 3 4 3 4-1.343 4-3M5 19h14" />
                                            </svg>
                                            <div>
                                                <p class="text-xs text-upsi-text-primary/60">Offered Price</p>
                                                <p class="text-sm font-medium text-upsi-text-primary">RM {{ number_format($serviceRequest->offered_price, 2) }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                        </svg>
                                        <div>
                                            <p class="text-xs text-upsi-text-primary/60">Category</p>
                                            <p class="text-sm font-medium text-upsi-text-primary">{{ optional($serviceRequest->studentService->category)->name ?? '—' }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-upsi-blue flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                                        </svg>
                                        <div>
                                            <p class="text-xs text-upsi-text-primary/60">Status</p>
                                            <p class="text-sm font-medium text-upsi-text-primary">{{ $serviceRequest->formatted_status }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($serviceRequest->message)
                                    <div>
                                        <p class="text-sm text-upsi-text-primary/60 mb-2">Message</p>
                                        <p class="text-upsi-text-primary">{{ $serviceRequest->message }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Parties Involved -->
                    <div>
                        <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Parties Involved</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Provider -->
                            <div class="bg-upsi-light-gray rounded-xl p-4">
                                <p class="text-sm text-upsi-text-primary/60 mb-2">Service Provider</p>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('students.profile', $serviceRequest->provider) }}" class="block group/avatar">
                                        @if($serviceRequest->provider->profile_photo_path)
                                            <img src="{{ asset('storage/' . $serviceRequest->provider->profile_photo_path) }}" 
                                                 alt="{{ $serviceRequest->provider->name }}" 
                                                 class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($serviceRequest->provider->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('students.profile', $serviceRequest->provider) }}" class="font-semibold text-upsi-blue hover:text-upsi-blue/80">
                                            {{ $serviceRequest->provider->name }}
                                        </a>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @php($providerRating = round($serviceRequest->provider->average_rating ?? 0))
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $providerRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-upsi-text-primary/60">{{ number_format($serviceRequest->provider->average_rating ?? 0, 1) }}</span>
                                        </div>
                                        <p class="text-xs text-upsi-text-primary/60">{{ $serviceRequest->provider->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Requester -->
                            <div class="bg-upsi-light-gray rounded-xl p-4">
                                <p class="text-sm text-upsi-text-primary/60 mb-2">Requester</p>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('students.profile', $serviceRequest->requester) }}" class="block group/avatar">
                                        @if($serviceRequest->requester->profile_photo_path)
                                            <img src="{{ asset('storage/' . $serviceRequest->requester->profile_photo_path) }}" 
                                                 alt="{{ $serviceRequest->requester->name }}" 
                                                 class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($serviceRequest->requester->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('students.profile', $serviceRequest->requester) }}" class="font-semibold text-upsi-blue hover:text-upsi-blue/80">
                                            {{ $serviceRequest->requester->name }}
                                        </a>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                @php($requesterRating = round($serviceRequest->requester->average_rating ?? 0))
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $requesterRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-upsi-text-primary/60">{{ number_format($serviceRequest->requester->average_rating ?? 0, 1) }}</span>
                                        </div>
                                        <p class="text-xs text-upsi-text-primary/60">{{ $serviceRequest->requester->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Actions</h2>
                        <div class="flex items-center flex-wrap gap-4">
                            @if($isProvider && $serviceRequest->isPending())
                                <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'accept')" 
                                        class="px-6 py-3 bg-upsi-blue text-white font-semibold rounded-xl hover:bg-upsi-blue/90 transition-all duration-200">
                                    Accept Request
                                </button>
                                <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'reject')" 
                                        class="px-6 py-3 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all duration-200">
                                    Reject Request
                                </button>
                            @endif
                            @if($isProvider && $serviceRequest->isAccepted())
                                <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'in-progress')" 
                                        class="px-6 py-3 bg-indigo-50 text-indigo-600 font-semibold rounded-xl hover:bg-indigo-100 transition-all duration-200">
                                    Start Work
                                </button>
                            @endif
                            @if($isProvider && $serviceRequest->isInProgress())
                                <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'complete')" 
                                        class="px-6 py-3 bg-green-50 text-green-600 font-semibold rounded-xl hover:bg-green-100 transition-all duration-200">
                                    Mark Completed
                                </button>
                            @endif
                            @if(!$serviceRequest->isCompleted())
                                <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'cancel')" 
                                        class="px-6 py-3 bg-gray-50 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-all duration-200">
                                    Cancel
                                </button>
                            @endif
                            @if($serviceRequest->isCompleted() && !$serviceRequest->reviews()->where('reviewer_id', auth()->id())->exists())
                                <button onclick="openReviewModal({{ $serviceRequest->id }}, '{{ $isProvider ? $serviceRequest->requester->name : $serviceRequest->provider->name }}')" 
                                        class="px-6 py-3 bg-upsi-blue text-white font-semibold rounded-xl hover:bg-upsi-blue/90 transition-all duration-200">
                                    Leave Review
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($serviceRequest->reviews->count() > 0)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h2 class="text-lg font-semibold text-upsi-text-primary mb-4">Reviews</h2>
                            <div class="space-y-4">
                                @foreach($serviceRequest->reviews as $review)
                                    <div class="bg-upsi-light-gray rounded-xl p-4 border border-gray-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium text-upsi-text-primary">{{ $review->reviewer->name }}</span>
                                                <span class="text-sm text-upsi-text-primary/60">({{ $review->reviewer_id === $serviceRequest->provider_id ? 'Provider' : 'Customer' }})</span>
                                            </div>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="text-upsi-text-primary">{{ $review->comment }}</p>
                                        @endif
                                        <p class="text-xs text-upsi-text-primary/60 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Leave a Review</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="reviewForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="reviewServiceRequestId" name="service_request_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="star-button text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none">★</button>
                            @endfor
                        </div>
                        <input type="hidden" id="rating" name="rating" required>
                    </div>
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                        <textarea id="comment" name="comment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share your experience..."></textarea>
                    </div>
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeReviewModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReviewModal(serviceRequestId, userName) {
            document.getElementById('reviewServiceRequestId').value = serviceRequestId;
            document.getElementById('reviewModal').classList.remove('hidden');
        }
        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewForm').reset();
            resetStars();
        }
        function setRating(rating) {
            document.getElementById('rating').value = rating;
            document.querySelectorAll('.star-button').forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
        function resetStars() {
            document.querySelectorAll('.star-button').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('rating').value = '';
        }
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            try {
                const response = await fetch('/reviews', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                if (data.success) {
                    closeReviewModal();
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to submit review');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });

        async function updateRequestStatus(requestId, action) {
            try {
                const endpointMap = {
                    'accept': `/service-requests/${requestId}/accept`,
                    'reject': `/service-requests/${requestId}/reject`,
                    'in-progress': `/service-requests/${requestId}/mark-in-progress`,
                    'complete': `/service-requests/${requestId}/mark-completed`,
                    'cancel': `/service-requests/${requestId}/cancel`
                };
                const url = endpointMap[action];
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to update request');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    </script>
</x-app-layout>