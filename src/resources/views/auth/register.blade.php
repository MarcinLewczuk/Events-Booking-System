<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-primary-800">Create Your Account</h2>
        <p class="text-sm text-primary-600 mt-1">Join Fotherby's Auction House</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Title -->
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <select id="title" name="title" class="block mt-1 w-full border-primary-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select Title</option>
                <option value="Mr" {{ old('title') == 'Mr' ? 'selected' : '' }}>Mr</option>
                <option value="Mrs" {{ old('title') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                <option value="Miss" {{ old('title') == 'Miss' ? 'selected' : '' }}>Miss</option>
                <option value="Ms" {{ old('title') == 'Ms' ? 'selected' : '' }}>Ms</option>
                <option value="Dr" {{ old('title') == 'Dr' ? 'selected' : '' }}>Dr</option>
                <option value="Prof" {{ old('title') == 'Prof' ? 'selected' : '' }}>Prof</option>
            </select>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Surname -->
        <div class="mt-4">
            <x-input-label for="surname" :value="__('Surname')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contact Telephone -->
        <div class="mt-4">
            <x-input-label for="contact_telephone_number" :value="__('Contact Telephone')" />
            <x-text-input id="contact_telephone_number" class="block mt-1 w-full" type="tel" name="contact_telephone_number" :value="old('contact_telephone_number')" required />
            <x-input-error :messages="$errors->get('contact_telephone_number')" class="mt-2" />
        </div>

        <!-- Contact Address -->
        <div class="mt-4">
            <x-input-label for="contact_address" :value="__('Contact Address')" />
            <textarea id="contact_address" name="contact_address" rows="3" class="block mt-1 w-full border-primary-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('contact_address') }}</textarea>
            <x-input-error :messages="$errors->get('contact_address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="text-xs text-primary-500 mt-1">Minimum 8 characters</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-primary-600 hover:text-primary-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-primary-900 hover:bg-primary-600">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>