<x-guest-layout>
    <div class="bg-base-100 p-6 sm:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-upsi-dark">Community Verification</h1>
                    <span class="badge" style="background-color:#D4AF37;color:#1F2937;">Pengguna Disahkan</span>
                </div>
                <p class="text-gray-600">Complete verification to unlock trusted features.</p>
            </div>

            <!-- Steps -->
            <ul class="steps w-full">
                <li class="step step-primary">Phone OTP</li>
                <li class="step">Upload Photo</li>
                <li class="step">Live Selfie</li>
            </ul>

            <!-- Step 1: Phone OTP -->
            <div class="card bg-white border border-gray-200 mt-6">
                <div class="card-body">
                    <h2 class="card-title text-upsi-blue">Step 1: Verify Phone</h2>
                    <p class="text-gray-600">Enter the 6-digit code sent to your phone.</p>
                    <div class="flex items-center space-x-3 mt-4">
                        <input type="text" maxlength="6" placeholder="OTP Code" class="input input-bordered w-48" />
                        <button class="btn btn-primary">Verify</button>
                        <button class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Resend</button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Upload Profile Photo -->
            <div class="card bg-white border border-gray-200 mt-6">
                <div class="card-body">
                    <h2 class="card-title text-upsi-blue">Step 2: Upload Profile Photo</h2>
                    <p class="text-gray-600">Upload a clear photo of yourself.</p>
                    <input type="file" class="file-input file-input-bordered w-full max-w-md" />
                    <div class="mt-4">
                        <button class="btn btn-primary">Save Photo</button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Live Selfie Check -->
            <div class="card bg-white border border-gray-200 mt-6">
                <div class="card-body">
                    <h2 class="card-title text-upsi-blue">Step 3: Live Selfie Check</h2>
                    <p class="text-gray-600">Complete a live selfie match to confirm identity.</p>
                    <div class="bg-gray-100 rounded-xl border border-gray-200 p-6 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-700">Camera preview placeholder</p>
                            <p class="text-xs text-gray-500">Ensure good lighting and remove accessories.</p>
                        </div>
                        <button class="btn btn-primary">Open Camera</button>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('onboarding.register') }}" class="btn btn-outline" style="border-color:#003B73;color:#003B73;">Back</a>
                <a href="{{ route('community.home') }}" class="btn btn-primary">Finish Verification</a>
            </div>
        </div>
    </div>
</x-guest-layout>