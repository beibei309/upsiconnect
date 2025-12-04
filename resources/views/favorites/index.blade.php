<x-app-layout>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <br>
            <div class="mb-8 text-left mt-10">
                <h1 class="text-4xl font-bold text-gray-900">My Favorites</h1>
                <p class="text-gray-600 mt-2">Here are all the services you’ve added to your favorites.</p>
            </div>

            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favorites as $favorite)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-2xl transition-shadow duration-300">
                            
                            {{-- Header: avatar, name, badges --}}
                            <div class="p-5 pb-3 flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('students.profile', $favorite) }}">
                                        @if($favorite->profile_photo_path)
                                            <img src="{{ asset('storage/' . $favorite->profile_photo_path) }}"
                                                 alt="{{ $favorite->name }}"
                                                 class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-200 hover:ring-indigo-400 transition">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                                {{ substr($favorite->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('students.profile', $favorite) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition text-sm">
                                            {{ Str::limit($favorite->name, 25) }}
                                        </a>
                                        <div class="flex items-center space-x-1 mt-1">
                                            @if($favorite->trust_badge)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Verified</span>
                                            @endif
                                            {{-- Rating --}}
                                            <div class="flex items-center space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= ($favorite->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                                <span class="text-xs text-gray-500">({{ $favorite->reviews_count ?? 0 }})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Availability --}}
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $favorite->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <div class="w-2 h-2 rounded-full {{ $favorite->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-1"></div>
                                    {{ $favorite->is_available ? 'Available' : 'Busy' }}
                                </span>
                            </div>

                            {{-- Bio & role tags --}}
                            <div class="px-5 pb-3 flex-grow">
                                @if($favorite->bio)
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-3">{{ $favorite->bio }}</p>
                                @endif
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $favorite->role === 'student' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $favorite->role === 'student' ? 'Student Provider' : 'Community Member' }}
                                    </span>
                                    @if($favorite->role === 'student' && ($favorite->services_count ?? 0) > 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ $favorite->services_count }} {{ Str::plural('service', $favorite->services_count) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="px-5 pb-5 flex space-x-2 mt-auto">
                                <a href="{{ route('students.profile', $favorite) }}" class="flex-1 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition text-center">View Profile</a>

                                @if(auth()->user()->role === 'community' && $favorite->role === 'student')
                                    @if($favorite->is_available)
                                        <a href="{{ route('chat.request', ['user' => $favorite->id]) }}" class="flex-1 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition text-center">Contact</a>
                                    @else
                                        <button disabled class="flex-1 py-2 rounded-lg bg-gray-400 text-white text-sm cursor-not-allowed">Unavailable</button>
                                    @endif
                                @endif

                                <button type="button" onclick="removeFavorite({{ $favorite->id }})" class="py-2 px-3 rounded-lg border border-gray-300 text-red-600 hover:bg-red-50 transition">Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-lg rounded-xl p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No favorites yet</h3>
                    <p class="text-gray-500">Start adding providers to your favorites to keep track of them.</p>
                    <a href="{{ route('services.index') }}" class="mt-4 inline-flex px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Browse Services</a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        async function removeFavorite(userId) {
            if(!confirm('Are you sure you want to remove this user from favorites?')) return;
            try {
                const res = await fetch(`/favorites/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await res.json();
                if(data.success) window.location.reload();
                else alert(data.message || 'Failed to remove favorite');
            } catch(err) {
                console.error(err);
                alert('Error removing favorite');
            }
        }
    </script>
    @endpush
</x-app-layout>
