@extends('layouts.helper')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
<br>
        <div class="mb-8 text-left mt-10">
                <h1 class="text-4xl font-bold text-gray-900">My Chat</h1>
            </div>

            <!-- Chat Box -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200">
                <!-- Chat Header -->
                <div class="bg-black text-white p-4 rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 alt="User Avatar" 
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <h2 class="font-semibold text-lg">Chat Conversations</h2>
                                <p class="text-sm opacity-80">Manage your conversations with service providers</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 bg-gray-700 bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors text-sm">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Chat Requests Tabs -->
                @if((isset($pendingRequests) && $pendingRequests->count() > 0) || (isset($sentRequests) && $sentRequests->count() > 0))
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex space-x-4 mb-6">
                            @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                                <button onclick="showTab('pending')" id="pending-tab" 
                                        class="tab-button py-2 px-4 border-b-2 font-medium text-sm text-gray-800">
                                    Pending Requests
                                    <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-xs">
                                        {{ $pendingRequests->count() }}
                                    </span>
                                </button>
                            @endif
                            @if(isset($sentRequests) && $sentRequests->count() > 0)
                                <button onclick="showTab('sent')" id="sent-tab" 
                                        class="tab-button py-2 px-4 border-b-2 font-medium text-sm text-gray-500">
                                    Sent Requests
                                    <span class="ml-2 bg-gray-200 text-gray-700 py-0.5 px-2 rounded-full text-xs">
                                        {{ $sentRequests->count() }}
                                    </span>
                                </button>
                            @endif
                        </div>

                        <!-- Pending Requests Tab -->
                        @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                            <div id="pending-content" class="tab-content">
                                <h3 class="text-lg font-medium mb-4 text-gray-800">Chat Requests for You</h3>
                                <div class="space-y-4">
                                    @foreach($pendingRequests as $request)
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-100">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <img src="{{ $request->requester->profile_picture ? asset('storage/' . $request->requester->profile_picture) : asset('images/default-avatar.png') }}" 
                                                         alt="{{ $request->requester->name }}" 
                                                         class="w-12 h-12 rounded-full object-cover">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-800">{{ $request->requester->name }}</h4>
                                                        <p class="text-sm text-gray-600">Community Member</p>
                                                        @if($request->message)
                                                            <p class="text-sm text-gray-700 mt-2 bg-white p-2 rounded border">
                                                                "{{ $request->message }}"
                                                            </p>
                                                        @endif
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            Received {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2 ml-4">
                                                    <button onclick="acceptChatRequest({{ $request->id }})" 
        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
    Accept
</button>
<button onclick="declineChatRequest({{ $request->id }})" 
        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 transition-colors text-sm">
    Decline
</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Sent Requests Tab -->
                        @if(isset($sentRequests) && $sentRequests->count() > 0)
                            <div id="sent-content" class="tab-content" style="display: none;">
                                <h3 class="text-lg font-medium mb-4 text-gray-800">Your Sent Requests</h3>
                                <div class="space-y-4">
                                    @foreach($sentRequests as $request)
                                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-100">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-center space-x-3 flex-1">
                                                    <img src="{{ $request->recipient->profile_picture ? asset('storage/' . $request->recipient->profile_picture) : asset('images/default-avatar.png') }}" 
                                                         alt="{{ $request->recipient->name }}" 
                                                         class="w-12 h-12 rounded-full object-cover">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-800">{{ $request->recipient->name }}</h4>
                                                        <p class="text-sm text-gray-600">Service Provider</p>
                                                        @if($request->message)
                                                            <p class="text-sm text-gray-700 mt-2 bg-white p-2 rounded border">
                                                                "{{ $request->message }}"
                                                            </p>
                                                        @endif
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            Sent {{ $request->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                                        Pending Response
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Conversations List -->
                <div class="p-6">
                    @if(isset($conversations) && $conversations->count() > 0)
                        <div class="space-y-4">
                            @foreach($conversations as $conversation)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $conversation->otherUser->profile_picture ? asset('storage/' . $conversation->otherUser->profile_picture) : asset('images/default-avatar.png') }}" 
                                                 alt="{{ $conversation->otherUser->name }}" 
                                                 class="w-12 h-12 rounded-full object-cover">
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $conversation->otherUser->name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $conversation->otherUser->role === 'student' ? 'Service Provider' : 'Community Member' }}</p>
                                                @if($conversation->lastMessage)
                                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($conversation->lastMessage->content, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @if($conversation->lastMessage)
                                                <span class="text-xs text-gray-400">{{ $conversation->lastMessage->created_at->diffForHumans() }}</span>
                                            @endif
                                            <a href="{{ route('chat.show', $conversation) }}" 
                                               class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                                Open Chat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                            <p class="text-gray-600 mb-6">Start by contacting service providers you're interested in.</p>
                            <a href="{{ route('services.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Browse Services
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.style.display = 'none');

            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            const selectedContent = document.getElementById(tabName + '-content');
            selectedContent.style.display = 'block';

            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        document.addEventListener('DOMContentLoaded', function() {
    // Function to handle accept chat request
    window.acceptChatRequest = function(requestId) {
        // Confirm action
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to accept this chat request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, accept it!',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to accept the chat
                fetch(`/chat-requests/${requestId}/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.conversation) {
                        Swal.fire('Accepted!', 'The chat request has been accepted.', 'success')
                            .then(() => {
                                window.location.reload(); // Reload to update the state
                            });
                    } else {
                        Swal.fire('Error', 'Something went wrong while accepting the request.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                });
            }
        });
    }

    // Function to handle decline chat request
    window.declineChatRequest = function(requestId) {
        // Confirm action
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to decline this chat request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, decline it!',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to decline the chat
                fetch(`/chat-requests/${requestId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.chat_request) {
                        Swal.fire('Declined!', 'The chat request has been declined.', 'success')
                            .then(() => {
                                window.location.reload(); // Reload to update the state
                            });
                    } else {
                        Swal.fire('Error', 'Something went wrong while declining the request.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                });
            }
        });
    }
});

    </script>
@endsection
