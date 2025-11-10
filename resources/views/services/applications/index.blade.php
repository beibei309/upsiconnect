<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-upsi-text-primary">
                    @if(auth()->user()->role === 'student')
                        Open Applications
                    @else
                        My Applications
                    @endif
                </h1>
                <p class="mt-2 text-upsi-text-primary/60">
                    @if(auth()->user()->role === 'student')
                        Requests from community members open to all students
                    @else
                        Services you have applied for
                    @endif
                </p>
            </div>

            @if($applications->isEmpty())
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-upsi-light-gray rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-upsi-text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-upsi-text-primary mb-2">No applications yet</h3>
                    <p class="text-upsi-text-primary/60 mb-6">
                        @if(auth()->user()->role === 'student')
                            When community members apply for your services, they'll appear here.
                        @else
                            You haven't applied for any services yet.
                        @endif
                    </p>
                    @if(auth()->user()->isCommunity())
                        <a href="{{ route('search.index') }}" class="inline-flex items-center px-6 py-3 bg-upsi-blue text-white font-semibold rounded-xl hover:bg-upsi-blue/90 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Browse Services
                        </a>
                    @endif
                </div>
            @else
                <!-- Applications List -->
                <div class="space-y-4">
                    @foreach($applications as $application)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <!-- Service Info -->
                                        <div class="flex items-start space-x-4">
                                            @if($application->service && $application->service->images && count(json_decode($application->service->images, true)) > 0)
                                                <img src="{{ asset('storage/' . json_decode($application->service->images, true)[0]) }}" 
                                                     alt="{{ $application->service->title }}"
                                                     class="w-20 h-20 rounded-xl object-cover">
                                            @else
                                                <div class="w-20 h-20 bg-upsi-light-gray rounded-xl flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-upsi-text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-upsi-text-primary mb-1">
                                                    {{ $application->service->title ?? $application->title }}
                                                </h3>
                                                
                                                @if(auth()->user()->role === 'student')
                                                    <!-- Student view: show applicant info -->
                                                    <p class="text-sm text-upsi-text-primary/60 mb-2">
                                                        <span class="font-medium">Applicant:</span> {{ $application->user->name }}
                                                    </p>
                                                @else
                                                    <!-- Community view: show provider info -->
                                                    <p class="text-sm text-upsi-text-primary/60 mb-2">
                                                        <span class="font-medium">Provider:</span>
                                                        @if($application->service && $application->service->user)
                                                            {{ $application->service->user->name }}
                                                        @else
                                                            Awaiting provider responses
                                                        @endif
                                                    </p>
                                                @endif

                                                @if($application->message)
                                                    <p class="text-sm text-upsi-text-primary/80 mb-2">
                                                        <span class="font-medium">Message:</span> {{ Str::limit($application->message, 100) }}
                                                    </p>
                                                @endif

                                                <div class="flex items-center space-x-4 text-xs text-upsi-text-primary/60">
                                                    <span>Applied {{ $application->created_at->diffForHumans() }}</span>
                                                    @if($application->service)
                                                        <span>•</span>
                                                        <span>RM {{ number_format($application->service->price, 2) }}</span>
                                                    @elseif($application->budget_range)
                                                        <span>•</span>
                                                        <span>Budget: {{ $application->formatted_budget }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div>
                                        @php
                                            $statusColors = [
                                                'open' => 'bg-blue-100 text-blue-800',
                                                'pending' => 'bg-blue-100 text-blue-800',
                                                'accepted' => 'bg-green-100 text-green-800',
                                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-2">
                                        @if($application->status === 'open' && auth()->user()->role === 'student' && $application->service && $application->service->user_id === auth()->id())
                                            <button onclick="acceptApplication({{ $application->id }})"
                                                    class="px-4 py-2 bg-upsi-blue text-white text-sm font-semibold rounded-lg hover:bg-upsi-blue/90 transition-all duration-200">
                                                Accept Application
                                            </button>
                                            <button onclick="rejectApplication({{ $application->id }})"
                                                    class="px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-100 transition-all duration-200">
                                                Decline
                                            </button>
                                        @endif

                                        @if($application->status === 'in_progress')
                                            <button onclick="markCompleted({{ $application->id }})"
                                                    class="px-4 py-2 bg-green-50 text-green-600 text-sm font-semibold rounded-lg hover:bg-green-100 transition-all duration-200">
                                                Mark as Completed
                                            </button>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        @if(auth()->user()->role === 'community' && auth()->user()->id === $application->user_id && !$application->service && $application->status === 'open')
                                            <a href="{{ route('services.applications.edit', $application) }}" 
                                               class="text-upsi-blue hover:text-upsi-blue/80 text-sm font-semibold transition-colors">
                                                Edit Details
                                            </a>
                                        @endif
                                        <a href="{{ route('services.applications.show', $application) }}" 
                                           class="text-upsi-blue hover:text-upsi-blue/80 text-sm font-semibold transition-colors">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function acceptApplication(applicationId) {
            if (!confirm('Accept this application?')) return;

            fetch(`/services/applications/${applicationId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to accept application');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function rejectApplication(applicationId) {
            if (!confirm('Decline this application?')) return;

            fetch(`/services/applications/${applicationId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to decline application');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }

        function markCompleted(applicationId) {
            if (!confirm('Mark this service as completed?')) return;

            fetch(`/services/applications/${applicationId}/mark-completed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to mark as completed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    </script>
    @endpush
</x-app-layout>
