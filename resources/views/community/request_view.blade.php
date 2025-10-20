<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-3">
                            <h1 class="text-2xl font-bold text-gray-900">Incoming Chat Request</h1>
                            <span class="badge" style="background-color:#D4AF37;color:#1F2937;">Staf UPSI Rasmi</span>
                        </div>
                        <p class="text-gray-600 mt-2">A customer wants to chat with you about a service.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="rating rating-sm">
                            <input type="radio" class="mask mask-star-2 bg-yellow-500" checked />
                            <input type="radio" class="mask mask-star-2 bg-yellow-500" checked />
                            <input type="radio" class="mask mask-star-2 bg-yellow-500" checked />
                            <input type="radio" class="mask mask-star-2 bg-yellow-500" />
                            <input type="radio" class="mask mask-star-2 bg-yellow-500" />
                        </div>
                        <span class="text-sm text-gray-600">4.0</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="font-semibold text-gray-900">Message</h3>
                            <p class="text-gray-700 mt-2">Hello, I need tutoring in Calculus this weekend. Are you available?</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="font-semibold text-gray-900">Customer</h3>
                            <div class="flex items-center space-x-3 mt-2">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://api.dicebear.com/9.x/initials/svg?seed=AC" alt="avatar" />
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium">A. Customer</p>
                                    <p class="text-sm text-gray-600">Verified • 12 past reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="space-y-3">
                            <button class="btn btn-primary w-full">Accept</button>
                            <button class="btn w-full" style="background-color:#003B73;color:#FFFFFF;">View Profile</button>
                            <button class="btn btn-outline w-full" style="border-color:#003B73;color:#003B73;">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>