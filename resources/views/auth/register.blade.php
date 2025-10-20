<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
        <p class="text-gray-600">Join UpsiConnect and start connecting with students</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="name" 
                         class="input input-bordered w-full" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autofocus 
                         autocomplete="name" 
                         placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="email" 
                         class="input input-bordered w-full" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autocomplete="username" 
                         placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="password" 
                         class="input input-bordered w-full"
                         type="password"
                         name="password"
                         required 
                         autocomplete="new-password" 
                         placeholder="Create a strong password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 mb-2" />
            <x-text-input id="password_confirmation" 
                         class="input input-bordered w-full"
                         type="password"
                         name="password_confirmation" 
                         required 
                         autocomplete="new-password" 
                         placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms and Privacy -->
        <div class="flex items-start">
            <input id="terms" 
                   type="checkbox" 
                   class="checkbox checkbox-primary mt-1" 
                   required>
            <label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the 
                <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">Terms of Service</a> 
                and 
                <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="btn btn-primary w-full">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Already have an account?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-outline w-full">
                Sign In Instead
            </a>
        </div>
    </form>

    <!-- Back to Home -->
    <div class="mt-6 text-center">
        <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
            ← Back to Home
        </a>
    </div>
</x-guest-layout>
