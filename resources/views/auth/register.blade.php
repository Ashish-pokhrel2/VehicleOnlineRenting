<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <div class="flex gap-2">
                <!-- Country Selector -->
                <select name="country_code" id="country_code" 
                        class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        onchange="updatePhonePrefix()" required>
                    <option value="NP" {{ old('country_code', 'NP') === 'NP' ? 'selected' : '' }}>🇳🇵 Nepal</option>
                    <!-- Future country options will go here -->
                </select>
                
                <!-- Country Code Display -->
                <div id="phone_prefix" class="flex items-center justify-center border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md px-3 py-2 w-20">
                    +977
                </div>
                
                <!-- Phone Number Digits Input -->
                <x-text-input id="phone_digits" name="phone_digits" class="flex-1" type="tel" 
                             :value="old('phone_digits')" 
                             required 
                             placeholder="9812345678"
                             pattern="\d{10}"
                             maxlength="10"
                             title="Enter 10 digits" />
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter only the 10-digit phone number (e.g., 9812345678)</p>
            <x-input-error :messages="$errors->get('phone_digits')" class="mt-2" />
            <x-input-error :messages="$errors->get('country_code')" class="mt-2" />
        </div>

        <script>
            function updatePhonePrefix() {
                const countryCode = document.getElementById('country_code').value;
                const prefixDisplay = document.getElementById('phone_prefix');
                const phoneInput = document.getElementById('phone_digits');
                
                const prefixes = {
                    'NP': { prefix: '+977', digits: 10, placeholder: '9812345678' }
                    // Future countries will be added here
                };
                
                if (prefixes[countryCode]) {
                    prefixDisplay.textContent = prefixes[countryCode].prefix;
                    phoneInput.placeholder = prefixes[countryCode].placeholder;
                    phoneInput.maxLength = prefixes[countryCode].digits;
                    phoneInput.pattern = '\\d{' + prefixes[countryCode].digits + '}';
                }
            }
        </script>

        <!-- Register As -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">{{ __('Select role') }}</option>
                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                <option value="vendor" {{ old('role') === 'vendor' ? 'selected' : '' }}>{{ __('Vendor') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
