<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Favorites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favorites as $favorite)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
                            <!-- Header: avatar, name, badges, rating, availability -->
                            <div class="p-4 pb-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('students.profile', $favorite) }}" class="block group/avatar">
                                            @if($favorite->profile_photo_path)
                                                <img src="{{ asset('storage/' . $favorite->profile_photo_path) }}"
                                                     alt="{{ $favorite->name }}"
                                                     class="w-8 h-8 rounded-full object-cover ring-1 ring-gray-200 group-hover/avatar:ring-indigo-300">
                                            @else
                                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ substr($favorite->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div>
                                            <a href="{{ route('students.profile', $favorite) }}" class="font-medium text-gray-900 group-hover:text-indigo-600 transition-colors text-sm hover:underline">
                                                {{ Str::limit($favorite->name, 20) }}
                                            </a>
                                            <div class="flex items-center space-x-1 mt-0.5">
                                                @if($favorite->trust_badge)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-2.5 h-2.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Verified
                                                    </span>
                                                @endif

                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-2.5 h-2.5 {{ $i <= ($favorite->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs text-gray-500 ml-1">
                                                        ({{ $favorite->reviews_count ?? 0 }})
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $favorite->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <div class="w-1 h-1 rounded-full {{ $favorite->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-1"></div>
                                            {{ $favorite->is_available ? 'Available' : 'Busy' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Content: bio and role tags -->
                            <div class="px-4 pb-3 flex-grow">
                                @if($favorite->bio)
                                    <p class="text-xs text-upsi-text-primary mb-2 line-clamp-2">{{ $favorite->bio }}</p>
                                @endif
                                <div class="mb-2 min-h-[20px]">
                                    <div class="flex flex-wrap gap-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $favorite->role === 'student' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $favorite->role === 'student' ? 'Student Provider' : 'Community Member' }}
                                        </span>
                                        @if($favorite->role === 'student' && ($favorite->services_count ?? 0) > 0)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $favorite->services_count }} {{ Str::plural('service', $favorite->services_count) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions: always at bottom -->
                            <div class="px-4 pb-4 mt-auto">
                                <div class="flex space-x-2">
                                    <a href="{{ route('students.profile', $favorite) }}"
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                        View Profile
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @if(auth()->user()->role === 'community' && $favorite->role === 'student')
                                        @if($favorite->is_available)
                                            <a href="{{ route('chat.request', ['user' => $favorite->id]) }}"
                                               class="inline-flex items-center justify-center px-3 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors shadow-sm">
                                                Contact Provider
                                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <button disabled class="inline-flex items-center justify-center px-3 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-gray-400 cursor-not-allowed shadow-sm">
                                                Unavailable
                                            </button>
                                        @endif
                                    @endif
                                    <button type="button"
                                            onclick="removeFavorite({{ $favorite->id }})"
                                            class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No favorites yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start adding service providers to your favorites to keep track of them.</p>
                        <div class="mt-6">
                            <a href="{{ route('search.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Browse Services
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        async function removeFavorite(userId) {
            if (!confirm('Are you sure you want to remove this user from your favorites?')) {
                return;
            }

            try {
                const response = await fetch(`/favorites/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    // Reload the page to show updated favorites list
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to remove favorite');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while removing the favorite');
            }
        }
    </script>
    @endpush
</x-app-layout>
