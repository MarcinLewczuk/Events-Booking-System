<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-sm text-gray-600 mt-1">We're glad to see you again. Please sign in to continue.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Your Email')" />
            <x-text-input id="email" class="block mt-1 w-full border-primary-600" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Your Password')" />
            <x-text-input id="password" class="block mt-1 w-full border-primary-600"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Keep me signed in</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif

            <x-primary-button class="ms-3 bg-primary-900 hover:bg-primary-600">
                Sign In
            </x-primary-button>
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
            New to Delapré Abbey?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Create an account</a>
        </div>
    </form>
</x-guest-layout>
