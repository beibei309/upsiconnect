@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('service-requests.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Service Requests
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Service Request Details</h1>
        </div>

        <!-- Service Request Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <!-- Status Badge -->
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($serviceRequest->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($serviceRequest->status === 'accepted') bg-blue-100 text-blue-800
                    @elseif($serviceRequest->status === 'in_progress') bg-purple-100 text-purple-800
                    @elseif($serviceRequest->status === 'completed') bg-green-100 text-green-800
                    @elseif($serviceRequest->status === 'rejected') bg-red-100 text-red-800
                    @elseif($serviceRequest->status === 'cancelled') bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                </span>
            </div>

            <!-- Service Information -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $serviceRequest->studentService->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ $serviceRequest->studentService->description }}</p>
                    
                    <div class="space-y-2">
                        <p><span class="font-medium">Category:</span> {{ $serviceRequest->studentService->category->name }}</p>
                        <p><span class="font-medium">Base Price:</span> RM{{ number_format($serviceRequest->studentService->price, 2) }}</p>
                        @if($serviceRequest->offered_price)
                            <p><span class="font-medium">Offered Price:</span> RM{{ number_format($serviceRequest->offered_price, 2) }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        @if(auth()->user()->id === $serviceRequest->requester_id)
                            Service Provider
                        @else
                            Requester
                        @endif
                    </h3>
                    
                    @if(auth()->user()->id === $serviceRequest->requester_id)
                        <div class="flex items-center space-x-3">
                            <img src="{{ $serviceRequest->provider->profile_picture ? asset('storage/' . $serviceRequest->provider->profile_picture) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $serviceRequest->provider->name }}" 
                                 class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-medium">{{ $serviceRequest->provider->name }}</p>
                                <p class="text-sm text-gray-600">{{ $serviceRequest->provider->email }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <img src="{{ $serviceRequest->requester->profile_picture ? asset('storage/' . $serviceRequest->requester->profile_picture) : asset('images/default-avatar.png') }}" 
                                 alt="{{ $serviceRequest->requester->name }}" 
                                 class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <p class="font-medium">{{ $serviceRequest->requester->name }}</p>
                                <p class="text-sm text-gray-600">{{ $serviceRequest->requester->email }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Request Message -->
            @if($serviceRequest->message)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Request Message</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $serviceRequest->message }}</p>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div>
                            <p class="font-medium">Request Sent</p>
                            <p class="text-sm text-gray-600">{{ $serviceRequest->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($serviceRequest->accepted_at)
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="font-medium">Request Accepted</p>
                                <p class="text-sm text-gray-600">{{ $serviceRequest->accepted_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($serviceRequest->completed_at)
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <div>
                                <p class="font-medium">Service Completed</p>
                                <p class="text-sm text-gray-600">{{ $serviceRequest->completed_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()->id === $serviceRequest->provider_id)
                    <!-- Provider Actions -->
                    @if($serviceRequest->status === 'pending')
                        <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'accept')" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            Accept Request
                        </button>
                        <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'reject')" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Reject Request
                        </button>
                    @elseif($serviceRequest->status === 'accepted')
                        <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'mark-in-progress')" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                            Mark In Progress
                        </button>
                    @elseif($serviceRequest->status === 'in_progress')
                        <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'mark-completed')" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                            Mark Completed
                        </button>
                    @endif
                @endif

                @if($serviceRequest->status === 'completed')
                    <!-- Review Button for both parties -->
                    <button onclick="openReviewModal()" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium">
                        Leave Review
                    </button>
                @endif

                @if($serviceRequest->status !== 'completed' && $serviceRequest->status !== 'cancelled')
                    <!-- Cancel Button -->
                    <button onclick="updateRequestStatus({{ $serviceRequest->id }}, 'cancel')" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        Cancel Request
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Leave a Review</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="reviewForm">
                @csrf
                <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" 
                                    class="star text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none">
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>
                
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                    <textarea name="comment" id="comment" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReviewModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRating = 0;

function updateRequestStatus(requestId, action) {
    const actionMap = {
        'accept': 'accept',
        'reject': 'reject',
        'mark-in-progress': 'mark-in-progress',
        'mark-completed': 'mark-completed',
        'cancel': 'cancel'
    };
    
    const url = `/service-requests/${requestId}/${actionMap[action]}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the request');
    });
}

function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    resetReviewForm();
}

function setRating(rating) {
    currentRating = rating;
    document.getElementById('ratingInput').value = rating;
    
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function resetReviewForm() {
    currentRating = 0;
    document.getElementById('ratingInput').value = '';
    document.getElementById('comment').value = '';
    
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
}

document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (currentRating === 0) {
        alert('Please select a rating');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('{{ route("reviews.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeReviewModal();
        } else {
            alert(data.error || 'An error occurred while submitting the review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the review');
    });
});
</script>
@endsection