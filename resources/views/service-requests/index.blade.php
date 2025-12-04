@extends('layouts.helper')

@section('content')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Requests') }}
        </h2>
    </x-slot>

    @php($sentRequests = $sentRequests ?? collect())
    @php($receivedRequests = $receivedRequests ?? collect())
    @php($defaultTab = (auth()->user()->role === 'helper') ? 'received' : 'sent')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <br><br>
            <!-- Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="showTab('sent')" id="sent-tab" 
                                class="sr-tab-button py-2 px-1 border-b-2 font-medium text-sm {{ $defaultTab === 'sent' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Sent Requests
                            @if($sentRequests->count() > 0)
                                <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2 rounded-full text-xs">
                                    {{ $sentRequests->count() }}
                                </span>
                            @endif
                        </button>
                        <button onclick="showTab('received')" id="received-tab" 
                                class="sr-tab-button py-2 px-1 border-b-2 font-medium text-sm {{ $defaultTab === 'received' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Received Requests
                            @if($receivedRequests->count() > 0)
                                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">
                                    {{ $receivedRequests->count() }}
                                </span>
                            @endif
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Sent Requests Tab -->
            <div id="sent-content" class="sr-tab-content {{ $defaultTab === 'sent' ? '' : 'hidden' }}" style="{{ $defaultTab === 'sent' ? 'display:block' : 'display:none' }}">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Your Service Requests ({{ $sentRequests->count() }} total)</h3>
                        
                        @if($sentRequests->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.906-1.471c-.905-.405-1.967-.405-2.872 0L3.05 19.471c-.71.315-1.471-.215-1.471-.971V10.5c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No service requests</h3>
                                <p class="mt-1 text-sm text-gray-500">You haven't sent any service requests yet.</p>
                                <div class="mt-6">
                                    <a href="{{ route('search.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Browse Services
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($sentRequests as $request)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h4 class="text-lg font-medium text-gray-900">
                                                        {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                    </h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                        {{ $request->formatted_status }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-sm text-gray-600 mb-2">
                                                    Provider: {{ $request->provider->name }}
                                                </p>
                                                
                                                @if($request->message)
                                                    <p class="text-sm text-gray-700 mb-2">
                                                        <strong>Your message:</strong> {{ $request->message }}
                                                    </p>
                                                @endif
                                                
                                                @if($request->offered_price)
                                                    <p class="text-sm text-gray-700 mb-2">
                                                        <strong>Offered price:</strong> RM {{ number_format($request->offered_price, 2) }}
                                                        @if($request->studentService && !is_null($request->studentService->suggested_price))
                                                            <span class="text-xs text-gray-500">(Suggested: RM {{ number_format($request->studentService->suggested_price, 2) }})</span>
                                                        @endif
                                                    </p>
                                                @endif
                                                
                                                <p class="text-xs text-gray-500">
                                                    Requested {{ $request->created_at->diffForHumans() }}
                                                </p>
                                                <div class="mt-3">
                                                    <a href="{{ route('service-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
                                                </div>
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                @if($request->isPending())
                                                    <button onclick="cancelRequest({{ $request->id }})" 
                                                            class="px-3 py-1 text-sm text-red-600 hover:text-red-800">
                                                        Cancel
                                                    </button>
                                                @endif
                                                
                                                @if($request->isCompleted() && !$request->reviews()->where('reviewer_id', auth()->id())->exists())
                                                    <button onclick="openReviewModal({{ $request->id }}, '{{ $request->provider->name }}')" 
                                                            class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                        Leave Review
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Received Requests Tab -->
            <div id="received-content" class="sr-tab-content {{ $defaultTab === 'received' ? '' : 'hidden' }}" style="{{ $defaultTab === 'received' ? 'display:block' : 'display:none' }}">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Service Requests for You ({{ $receivedRequests->count() }} total)</h3>
                        
                        @if($receivedRequests->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V9a2 2 0 012 2v2m0 0h2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No service requests</h3>
                                <p class="mt-1 text-sm text-gray-500">You haven't received any service requests yet.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($receivedRequests as $request)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h4 class="text-lg font-medium text-gray-900">
                                                        {{ optional($request->studentService)->title ?? 'Custom Request' }}
                                                    </h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_color }}">
                                                        {{ $request->formatted_status }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-sm text-gray-600 mb-2">
                                                    From: {{ $request->requester->name }}
                                                </p>
                                                
                                                @if($request->message)
                                                    <p class="text-sm text-gray-700 mb-2">
                                                        <strong>Message:</strong> {{ $request->message }}
                                                    </p>
                                                @endif
                                                
                                                @if($request->offered_price)
                                                    <p class="text-sm text-gray-700 mb-2">
                                                        <strong>Offered price:</strong> RM {{ number_format($request->offered_price, 2) }}
                                                        @if($request->studentService && !is_null($request->studentService->suggested_price))
                                                            <span class="text-xs text-gray-500">
                                                                (Your suggested price: RM {{ number_format($request->studentService->suggested_price, 2) }})
                                                            </span>
                                                        @endif
                                                    </p>
                                                @endif
                                                
                                                <p class="text-xs text-gray-500">
                                                    Received {{ $request->created_at->diffForHumans() }}
                                                </p>
                                                <div class="mt-3">
                                                    <a href="{{ route('service-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
                                                </div>
                                            </div>
                                            
                                            <div class="flex space-x-2">
                                                @if($request->isPending())
                                                    <button onclick="acceptRequest({{ $request->id }})" 
                                                            class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                                        Accept
                                                    </button>
                                                    <button onclick="rejectRequest({{ $request->id }})" 
                                                            class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                                        Reject
                                                    </button>
                                                @endif
                                                
                                                @if($request->isAccepted())
                                                    <button onclick="markInProgress({{ $request->id }})" 
                                                            class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                                        Start Work
                                                    </button>
                                                @endif
                                                
                                                @if($request->isInProgress())
                                                    <button onclick="markCompleted({{ $request->id }})" 
                                                            class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                        Mark Complete
                                                    </button>
                                                @endif
                                                
                                                @if($request->isCompleted() && !$request->reviews()->where('reviewer_id', auth()->id())->exists())
                                                    <button onclick="openReviewModal({{ $request->id }}, '{{ $request->requester->name }}')" 
                                                            class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                        Leave Review
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
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
                        <textarea id="comment" name="comment" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Share your experience..."></textarea>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeReviewModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize default tab based on user role
        document.addEventListener('DOMContentLoaded', function () {
            showTab('{{ $defaultTab }}');
        });
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.sr-tab-content').forEach(content => {
                content.classList.add('hidden');
                content.style.display = 'none';
            });
            
            // Remove active styles from all tabs
            document.querySelectorAll('.sr-tab-button').forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            const target = document.getElementById(tabName + '-content');
            target.classList.remove('hidden');
            target.style.display = '';
            
            // Add active styles to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        async function acceptRequest(requestId) {
            await updateRequestStatus(requestId, 'accept', 'Accepting request...');
        }

        async function rejectRequest(requestId) {
            await updateRequestStatus(requestId, 'reject', 'Rejecting request...');
        }

        async function markInProgress(requestId) {
            await updateRequestStatus(requestId, 'in-progress', 'Starting work...');
        }

        async function markCompleted(requestId) {
            await updateRequestStatus(requestId, 'complete', 'Marking as completed...');
        }

        async function cancelRequest(requestId) {
            await updateRequestStatus(requestId, 'cancel', 'Cancelling request...');
        }

        async function updateRequestStatus(requestId, action, loadingText) {
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
    </script>
@endsection
