@extends('layouts.helper')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <br>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            @php
                $roleMessage = match (Auth::user()->role) {
                    'helper' => 'Manage your services and connect with the community',
                    'community' => 'Discover talented UPSI students and their services',
                    default => 'Manage your profile and platform activities',
                };
            @endphp
            <p class="text-gray-600 mt-2">{{ $roleMessage }}</p>
        </div>

        @if (Auth::user()->role === 'helper')
            <!-- HELPER DASHBOARD: Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Availability Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full flex flex-col justify-between">
                    <div x-data="availabilityComponent()">
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Availability</h3>
                            
                            <div class="flex items-start gap-3 text-sm text-gray-600">
                                <i class="fa-regular fa-calendar text-lg mt-0.5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 mb-1">Scheduled unavailability:</p>
                                    
                                    <template x-if="!isAvailable && startDate && endDate">
                                        <p class="text-gray-700 font-medium">
                                            From <span x-text="formatDate(startDate)"></span> to <span x-text="formatDate(endDate)"></span>
                                        </p>
                                    </template>
                                    
                                    <template x-if="isAvailable">
                                        <p class="text-gray-500 italic">You are currently available for new orders.</p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <button @click="openModal()" 
                            class="w-full mt-4 py-2 px-4 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors shadow-sm text-center">
                            Edit
                        </button>

                        <div x-show="showModal" 
                             class="fixed inset-0 flex items-center justify-center z-50 px-4" 
                             style="display: none;">
                            
                            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal()"></div>

                            <div class="bg-white rounded-xl shadow-xl w-full max-w-md z-10 overflow-hidden transform transition-all">
                                
                                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                    <h2 class="text-xl font-bold text-gray-900">Edit your availability</h2>
                                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                        <i class="fa-solid fa-times text-lg"></i>
                                    </button>
                                </div>

                                <div class="p-6 space-y-6">
                                    <p class="text-gray-600 text-sm">
                                        While unavailable, your Gigs are hidden and you will not receive new orders.
                                    </p>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">First day</label>
                                            <input type="date" x-model="startDate" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Last day</label>
                                            <input type="date" x-model="endDate" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <span class="text-sm font-medium text-gray-900">I am currently available</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" x-model="isAvailable">
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-indigo-100 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                                    <button @click="deleteDates()" x-show="!isAvailable" class="text-red-600 text-sm font-medium hover:underline">
                                        Delete scheduled dates
                                    </button>
                                    <span x-show="isAvailable"></span> <button @click="saveChanges()" class="px-6 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    function availabilityComponent() {
                        return {
                            isAvailable: {{ Auth::user()->is_available ? 'true' : 'false' }},
                            showModal: false,
                            // Ambil tarikh dari database jika ada, format YYYY-MM-DD
                            startDate: '{{ Auth::user()->unavailable_start_date ?? "" }}', 
                            endDate: '{{ Auth::user()->unavailable_end_date ?? "" }}',

                            openModal() {
                                this.showModal = true;
                            },

                            closeModal() {
                                this.showModal = false;
                            },
                            
                            // Fungsi format tarikh (Contoh: Dec 10, 2025)
                            formatDate(dateString) {
                                if(!dateString) return '';
                                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                                return new Date(dateString).toLocaleDateString('en-US', options);
                            },

                            deleteDates() {
                                this.startDate = '';
                                this.endDate = '';
                                this.isAvailable = true;
                                // Opsional: Terus save atau biar user tekan Save Changes
                            },

                            saveChanges() {
                                // Jika user set available = true, kosongkan tarikh
                                if (this.isAvailable) {
                                    this.startDate = null;
                                    this.endDate = null;
                                } else {
                                    if (!this.startDate || !this.endDate) {
                                        alert('Please select both start and end dates.');
                                        return;
                                    }
                                }

                                fetch('/availability/update-settings', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        is_available: this.isAvailable,
                                        start_date: this.startDate,
                                        end_date: this.endDate
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    // alert(data.message);
                                    this.showModal = false;
                                    // Reload page atau update UI state sahaja
                                    // window.location.reload(); 
                                })
                                .catch(err => console.error(err));
                            }
                        }
                    }
                </script>

                    </div>
                    <div class="mt-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ Auth::user()->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <div
                                class="w-2 h-2 rounded-full {{ Auth::user()->is_available ? 'bg-green-400' : 'bg-red-400' }} mr-2">
                            </div>
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
                        <a href="{{ route('services.manage') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
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
                        <div class="text-2xl font-bold text-green-600">
                            {{ Auth::user()->chatRequestsReceived()->where('status', 'pending')->count() }}</div>
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
                        [
                            'title' => 'Add Service',
                            'desc' => 'Create a new service',
                            'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
                            'bg' => 'bg-indigo-100',
                            'hover' => 'group-hover:bg-indigo-200',
                            'route' => route('services.create'),
                        ],
                        [
                            'title' => 'Manage Services',
                            'desc' => 'Edit existing services',
                            'icon' =>
                                'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                            'bg' => 'bg-green-100',
                            'hover' => 'group-hover:bg-green-200',
                            'route' => route('services.manage'),
                        ],
                        [
                            'title' => 'Messages',
                            'desc' => 'Chat with community',
                            'icon' =>
                                'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                            'bg' => 'bg-blue-100',
                            'hover' => 'group-hover:bg-blue-200',
                            'route' => route('chat.index'),
                        ],
                        [
                            'title' => 'My Profile',
                            'desc' => 'Update information',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            'bg' => 'bg-yellow-100',
                            'hover' => 'group-hover:bg-yellow-200',
                            'route' => route('students.edit'),
                        ],
                    ];
                @endphp

                @foreach ($actions as $action)
                    <a href="{{ $action['route'] }}"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 {{ $action['bg'] }} rounded-lg flex items-center justify-center {{ $action['hover'] }} transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $action['icon'] }}"></path>
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

        <!-- Dropdown range -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-4">

            <div class="flex items-center justify-between mb-4">

                <h2 class="text-xl font-semibold text-gray-900">Overview</h2>

                <form method="GET">
                    <select name="range" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-custom-teal focus:border-custom-teal">
                        <option value="30days" {{ ($range ?? '30days') === '30days' ? 'selected' : '' }}>Last 30 days
                        </option>
                        <option value="3months" {{ ($range ?? '') === '3months' ? 'selected' : '' }}>Last 3 months</option>
                        <option value="yearly" {{ ($range ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </form>

            </div>

            <div style="width: 100%; height: 260px;">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labels = @json($labels ?? []);
            const sales = @json($sales ?? []);
            const cancelled = @json($cancelled ?? []);
            const completed = @json($completedDaily ?? []);
            const newOrders = @json($newOrders ?? []);

            const ctx = document.getElementById('overviewChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Sales ($)',
                            data: sales,
                            borderWidth: 2,
                            borderColor: '#5ADBE8',
                            pointBackgroundColor: '#5ADBE8',
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Cancelled ($)',
                            data: cancelled,
                            borderWidth: 2,
                            borderColor: '#B0B0B0',
                            pointBackgroundColor: '#B0B0B0',
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Completed',
                            data: completed,
                            borderWidth: 2,
                            borderColor: '#0A1A5C',
                            pointBackgroundColor: '#0A1A5C',
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'New Orders',
                            data: newOrders,
                            borderWidth: 2,
                            borderColor: '#2ECC71',
                            pointBackgroundColor: '#2ECC71',
                            fill: false,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            labels: {
                                usePointStyle: true
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>


    </div>
    </div>
@endsection
