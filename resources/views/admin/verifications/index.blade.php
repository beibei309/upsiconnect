<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Community Verifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        @forelse($pending as $user)
                            <div class="border rounded-lg p-4 flex items-center justify-between">
                                <div>
                                    <div class="font-semibold">{{ $user->name }} ({{ $user->email }})</div>
                                    <div class="text-sm text-gray-600">Phone: {{ $user->phone ?? 'N/A' }}</div>
                                    <div class="text-sm">Status: {{ $user->verification_status }}</div>
                                </div>
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ url('/admin/verifications/'.$user->id.'/approve') }}">
                                        @csrf
                                        <x-primary-button>Approve</x-primary-button>
                                    </form>
                                    <form method="POST" action="{{ url('/admin/verifications/'.$user->id.'/reject') }}">
                                        @csrf
                                        <x-danger-button>Reject</x-danger-button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>No pending verifications.</p>
                        @endforelse
                    </div>
                    <div class="mt-6">
                        {{ $pending->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>