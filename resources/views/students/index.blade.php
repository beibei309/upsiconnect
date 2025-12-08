@extends('layouts.helper')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <br><br><br><br>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            @php
                $roleMessage = match(Auth::user()->role) {
                    'helper' => 'Manage your services and connect with the community',
                    'community' => 'Discover talented UPSI students and their services',
                    default => 'Manage your profile and platform activities',
                };
            @endphp
            <p class="text-gray-600 mt-2">{{ $roleMessage }}</p>
        </div>

        @if(Auth::user()->role === 'helper')
            <!-- HELPER DASHBOARD: Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Availability Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Availability Status</h3>
                            <p class="text-sm text-gray-600 mt-1">Toggle your availability for new requests</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="availability-toggle"
                                       class="sr-only peer"
                                       {{ Auth::user()->is_available ? 'checked' : '' }}
                                       x-data="{ toggle() {
                                            fetch('/availability/toggle', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                                }
                                            })
                                            .then(res => res.json())
                                            .then(data => window.location.reload())
                                            .catch(err => console.error(err));
                                        }}"
                                       @change="toggle()">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-indigo-600 relative after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ Auth::user()->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <div class="w-2 h-2 rounded-full {{ Auth::user()->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-2"></div>
                            {{ Auth::user()->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>

                <!-- My Services -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">My Services</h3>
                            <p class="text-sm text-gray-600 mt-1">Services you're offering</p>
                        </div>
                        <div class="text-2xl font-bold text-indigo-600">{{ Auth::user()->studentServices()->count() }}</div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('services.manage') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                            Manage Services →
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                            <p class="text-sm text-gray-600 mt-1">Your latest interactions</p>
                        </div>
                        <div class="text-2xl font-bold text-green-600">{{ Auth::user()->chatRequestsReceived()->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Pending chat requests</p>
                    </div>
                </div>
            </div>

            <!-- Helper Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $actions = [
                        ['title'=>'Add Service','desc'=>'Create a new service','icon'=>'M12 6v6m0 0v6m0-6h6m-6 0H6','bg'=>'bg-indigo-100','hover'=>'group-hover:bg-indigo-200','route'=>route('services.create')],
                        ['title'=>'Manage Services','desc'=>'Edit existing services','icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10','bg'=>'bg-green-100','hover'=>'group-hover:bg-green-200','route'=>route('services.manage')],
                        ['title'=>'Messages','desc'=>'Chat with community','icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z','bg'=>'bg-blue-100','hover'=>'group-hover:bg-blue-200','route'=>route('chat.index')],
                        ['title'=>'My Profile','desc'=>'Update information','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','bg'=>'bg-yellow-100','hover'=>'group-hover:bg-yellow-200','route'=>route('profile.edit')],
                    ];
                @endphp

                @foreach($actions as $action)
                    <a href="{{ $action['route'] }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 {{ $action['bg'] }} rounded-lg flex items-center justify-center {{ $action['hover'] }} transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">{{ $action['title'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $action['desc'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        @endif

        <!-- Recent Activity Section (Common for all roles) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900"><span class="font-medium">Profile updated</span></p>
                        <p class="text-sm text-gray-500">You updated your profile information</p>
                        <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                    View all activity →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
