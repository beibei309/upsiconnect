<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Chat Header -->
            <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/default-avatar.png') }}" 
                             alt="User Avatar" 
                             class="w-10 h-10 rounded-full">
                        <div>
                            <h2 class="font-semibold">Chat Conversations</h2>
                            <p class="text-sm opacity-90">Manage your conversations with service providers</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Pending Chat Requests Section -->
            @if((isset($pendingRequests) && $pendingRequests->count() > 0) || (isset($sentRequests) && $sentRequests->count() > 0))
                <div class="border-b border-gray-200 p-6">
                    <div class="flex space-x-1 mb-6">
                        @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                            <button onclick="showTab('pending')" id="pending-tab" 
                                    class="tab-button py-2 px-4 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                                Pending Requests
                                <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2 rounded-full text-xs">
                                    {{ $pendingRequests->count() }}
                                </span>
                            </button>
                        @endif
                        @if(isset($sentRequests) && $sentRequests->count() > 0)
                            <button onclick="showTab('sent')" id="sent-tab" 
                                    class="tab-button py-2 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Sent Requests
                                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">
                                    {{ $sentRequests->count() }}
                                </span>
                            </button>
                        @endif
                    </div>

                    <!-- Pending Requests Tab -->
                    @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                        <div id="pending-content" class="tab-content">
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Chat Requests for You</h3>
                            <div class="space-y-4">
                                @foreach($pendingRequests as $request)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-yellow-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <img src="{{ $request->requester->profile_picture ? asset('storage/' . $request->requester->profile_picture) : asset('images/default-avatar.png') }}" 
                                                     alt="{{ $request->requester->name }}" 
                                                     class="w-12 h-12 rounded-full object-cover">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">{{ $request->requester->name }}</h4>
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
                                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                    Accept
                                                </button>
                                                <button onclick="declineChatRequest({{ $request->id }})" 
                                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
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
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Your Sent Requests</h3>
                            <div class="space-y-4">
                                @foreach($sentRequests as $request)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <img src="{{ $request->recipient->profile_picture ? asset('storage/' . $request->recipient->profile_picture) : asset('images/default-avatar.png') }}" 
                                                     alt="{{ $request->recipient->name }}" 
                                                     class="w-12 h-12 rounded-full object-cover">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">{{ $request->recipient->name }}</h4>
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
                                            <h3 class="font-semibold text-gray-900">{{ $conversation->otherUser->name }}</h3>
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
                                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
                        <a href="{{ route('search.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active styles from all tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(tabName + '-content');
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
    
    // Add active styles to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-indigo-500', 'text-indigo-600');
    }
}

async function acceptChatRequest(requestId) {
    try {
        const response = await fetch(`/chat-requests/${requestId}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Show success message
            showAlert('Chat request accepted! A new conversation has been created.', 'success');
            
            // Reload page after 2 seconds to show the new conversation
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showAlert(data.error || 'Failed to accept chat request', 'error');
        }
    } catch (error) {
        console.error('Error accepting chat request:', error);
        showAlert('An error occurred while accepting the chat request', 'error');
    }
}

async function declineChatRequest(requestId) {
    try {
        const response = await fetch(`/chat-requests/${requestId}/decline`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Show success message
            showAlert('Chat request declined.', 'success');
            
            // Remove the request from the UI
            const requestElement = event.target.closest('.border');
            if (requestElement) {
                requestElement.remove();
            }
            
            // Reload page after 1 second to update counts
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert(data.error || 'Failed to decline chat request', 'error');
        }
    } catch (error) {
        console.error('Error declining chat request:', error);
        showAlert('An error occurred while declining the chat request', 'error');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    alertDiv.className = `fixed top-4 right-4 ${bgColor} px-6 py-4 rounded-lg z-50 shadow-lg border`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' 
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                }
            </svg>
            <p class="font-medium">${message}</p>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Remove alert after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Initialize the first tab as active if there are pending requests
document.addEventListener('DOMContentLoaded', function() {
    const pendingTab = document.getElementById('pending-tab');
    const sentTab = document.getElementById('sent-tab');
    
    if (pendingTab) {
        showTab('pending');
    } else if (sentTab) {
        showTab('sent');
    }
});
</script>
</x-app-layout>