@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Chat Header -->
            <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('chat.index') }}" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        @php
                            $otherUser = $conversation->student_id === auth()->id() ? $conversation->customer : $conversation->student;
                        @endphp
                        <img src="{{ $otherUser->profile_picture ? asset('storage/' . $otherUser->profile_picture) : asset('images/default-avatar.png') }}" 
                             alt="{{ $otherUser->name }}" 
                             class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h2 class="font-semibold">{{ $otherUser->name }}</h2>
                            <p class="text-sm opacity-90">{{ $otherUser->role === 'student' ? 'Service Provider' : 'Community Member' }}</p>
                        </div>
                    </div>
                    
                    <!-- Service Application Button (for community users chatting with students) -->
                    @if(auth()->user()->role === 'community' && $otherUser->role === 'student')
                        <button onclick="openServiceApplicationModal()" 
                                class="px-4 py-2 bg-green-500 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                            Apply for Service
                        </button>
                    @endif
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50">
                @forelse($conversation->messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-end space-x-2 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <img src="{{ $message->sender->profile_picture ? asset('storage/' . $message->sender->profile_picture) : asset('images/default-avatar.png') }}" 
                                     alt="{{ $message->sender->name }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                                <div class="px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200' }}">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Service Applications Status -->
            @if($serviceApplications->count() > 0)
                <div class="border-t border-gray-200 p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Applications</h3>
                    <div class="space-y-3">
                        @foreach($serviceApplications as $application)
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $application->service->title ?? $application->title }}</h4>
                                        <p class="text-sm text-gray-600">Applied by {{ $application->user->name }}</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                @elseif($application->status === 'declined') bg-red-100 text-red-800
                                                @elseif($application->status === 'completed') bg-blue-100 text-blue-800
                                                @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                            
                                            @if($application->status === 'accepted')
                                                @if($application->customer_completed && $application->provider_completed)
                                                    <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                                        Fully Completed
                                                    </span>
                                                @elseif($application->customer_completed)
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                        Customer Completed
                                                    </span>
                                                @elseif($application->provider_completed)
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                        Provider Completed
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        @if($application->status === 'pending' && auth()->user()->id === $application->service->user_id)
                                            <!-- Provider can accept/decline -->
                                            <button onclick="handleApplication({{ $application->id }}, 'accept')" 
                                                    class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                Accept
                                            </button>
                                            <button onclick="handleApplication({{ $application->id }}, 'decline')" 
                                                    class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                Decline
                                            </button>
                                        @elseif($application->status === 'accepted' && $application->canBeMarkedCompletedBy(auth()->user()))
                                            <!-- Both parties can mark as completed -->
                                            <button onclick="markCompleted({{ $application->id }})" 
                                                    class="px-3 py-1 bg-purple-600 text-white text-sm rounded hover:bg-purple-700">
                                                Mark Completed
                                            </button>
                                        @elseif($application->status === 'completed' && $application->isFullyCompleted())
                                            <!-- Show review button -->
                                            <button onclick="openReviewModal({{ $application->id }})" 
                                                    class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">
                                                Leave Review
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Message Input -->
            <div class="border-t border-gray-200 p-4">
                <form id="messageForm" class="flex space-x-3">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <input type="text" 
                           name="message" 
                           id="messageInput"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Type your message..." 
                           required>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Send
                    </button>
                </form>
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
                <input type="hidden" id="reviewServiceApplicationId" name="service_application_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rating
                    </label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" 
                                    class="star-button text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none">
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" id="rating" name="rating" required>
                </div>
                
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Comment (Optional)
                    </label>
                    <textarea id="comment" name="comment" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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

<!-- Service Application Modal -->
@if(auth()->user()->role === 'community')
<div id="serviceApplicationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Apply for Service</h3>
                <button onclick="closeServiceApplicationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="serviceApplicationForm" class="space-y-4">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Select Service</label>
                    <select name="service_id" id="service_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Choose a service...</option>
                        @foreach($providerServices as $service)
                            <option value="{{ $service->id }}">{{ $service->title }} - {{ $service->price_range ?? 'Price negotiable' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="application_message" class="block text-sm font-medium text-gray-700 mb-2">Additional Message (Optional)</label>
                    <textarea name="message" id="application_message" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any specific requirements or questions..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeServiceModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Apply for Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
// Message sending functionality
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    try {
        const response = await fetch('{{ route("messages.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            messageInput.value = '';
            // Add message to chat (you might want to implement real-time updates)
            location.reload(); // Simple reload for now
        } else {
            throw new Error('Failed to send message');
        }
    } catch (error) {
        alert('Failed to send message. Please try again.');
    }
});

// Service application handling
async function handleApplication(applicationId, action) {
    try {
        const response = await fetch(`/service-applications/${applicationId}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            alert(data.message);
            location.reload();
        } else {
            throw new Error(data.message || `Failed to ${action} application`);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Mark service as completed
async function markCompleted(applicationId) {
    if (!confirm('Are you sure you want to mark this service as completed?')) {
        return;
    }
    
    try {
        const response = await fetch(`/service-applications/${applicationId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            alert(data.message);
            location.reload();
        } else {
            throw new Error(data.message || 'Failed to mark as completed');
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Review modal function (placeholder)
function openReviewModal(applicationId) {
        // Set the service application ID in a hidden form field
        document.getElementById('reviewServiceApplicationId').value = applicationId;
        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.getElementById('reviewForm').reset();
        resetStars();
    }

    function setRating(rating) {
        document.getElementById('rating').value = rating;
        
        // Update star display
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

    // Handle review form submission
    document.getElementById('reviewForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const rating = document.getElementById('rating').value;
        if (!rating) {
            alert('Please select a rating');
            return;
        }
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                closeReviewModal();
                location.reload(); // Refresh to update the UI
            } else {
                alert(data.error || 'An error occurred while submitting the review');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while submitting the review');
        }
    });

// Service application modal functions
function openServiceApplicationModal() {
    // Load available services for the provider
    loadProviderServices();
    document.getElementById('serviceApplicationModal').classList.remove('hidden');
}

function closeServiceModal() {
            document.getElementById('serviceApplicationModal').classList.add('hidden');
        }

async function loadProviderServices() {
    const providerId = document.querySelector('input[name="provider_id"]').value;
    const serviceSelect = document.getElementById('service_id');
    
    try {
        const response = await fetch(`/api/users/${providerId}/services`);
        const services = await response.json();
        
        serviceSelect.innerHTML = '<option value="">Choose a service...</option>';
        services.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = `${service.title} - $${service.price}`;
            serviceSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Failed to load services:', error);
    }
}

// Service application form submission
document.getElementById('serviceApplicationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';
    
    try {
        const response = await fetch('{{ route("service-applications.apply") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            closeServiceModal();
            // Show success message
            alert('Service application submitted successfully!');
            // Optionally redirect to service requests page
            window.location.href = '{{ route("service-requests.index") }}';
        } else {
            throw new Error(data.error || 'Failed to submit application');
        }
    } catch (error) {
        alert('Failed to submit application: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

// Close modal when clicking outside
document.getElementById('serviceApplicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeServiceApplicationModal();
    }
});
</script>
@endsection