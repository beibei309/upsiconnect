<x-app-layout>
    <div class="h-[calc(100vh-6rem)]">
        <!-- Chat Header -->
        <div class="w-full bg-upsi-blue text-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="avatar">
                        <div class="w-8 rounded-full">
                            <img src="https://api.dicebear.com/9.x/initials/svg?seed=AC" alt="avatar" />
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold">A. Customer</p>
                        <p class="text-xs opacity-75">Verified • Online</p>
                    </div>
                </div>
                <a href="#" class="btn btn-outline btn-sm" style="border-color:#D81B27;color:#FFFFFF;">Report User</a>
            </div>
        </div>

        <!-- Chat Body -->
        <div class="max-w-5xl mx-auto h-full grid grid-rows-[1fr_auto]">
            <div class="overflow-y-auto p-6 space-y-4 bg-base-100">
                <div class="chat chat-start">
                    <div class="chat-header">
                        A. Customer
                        <time class="text-xs opacity-50 ml-2">10:00</time>
                    </div>
                    <div class="chat-bubble">Hi! Can you help with study planning?</div>
                </div>
                <div class="chat chat-end">
                    <div class="chat-header">
                        You
                        <time class="text-xs opacity-50 ml-2">10:01</time>
                    </div>
                    <div class="chat-bubble" style="background-color:#003B73;color:#FFFFFF;">Sure, let's discuss your goals.</div>
                </div>
            </div>

            <div class="border-t border-gray-200 bg-white">
                <div class="max-w-5xl mx-auto flex items-center space-x-3 p-3">
                    <input type="text" class="input input-bordered w-full" placeholder="Type a message" />
                    <button class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>