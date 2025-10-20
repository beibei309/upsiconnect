<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-900">Chat Request</h1>
                    <span class="badge" style="background-color:#D4AF37;color:#1F2937;">Pengguna Disahkan</span>
                </div>
                <p class="text-gray-600 mt-2">Approve or decline this conversation request.</p>

                <div class="mt-6 space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-gray-900">Request Details</h3>
                        <p class="text-gray-700 mt-2">Customer is requesting a 30-minute consultation on study techniques.</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button class="btn btn-primary">Accept</button>
                        <button class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Decline</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>