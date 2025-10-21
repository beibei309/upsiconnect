@extends('layouts.app')

@section('content')
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
@endsection