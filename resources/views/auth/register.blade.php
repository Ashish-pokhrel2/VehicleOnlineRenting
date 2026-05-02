<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="simple-login-page">

    <div class="simple-login-wrapper">
        <div class="simple-login-logo-wrap">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="simple-login-logo">
            </a>
        </div>

        <div class="simple-login-card">
            <h1 class="simple-login-title">Create an Account</h1>
            <p class="simple-login-subtitle">Join VehicleRent and start booking today</p>

            <form method="POST" action="{{ route('register') }}" class="simple-login-form" novalidate>
                @csrf

                <input type="hidden" name="role" value="customer">

                <div class="simple-login-field">
                    <label for="name" class="simple-login-label">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        autofocus class="simple-login-input" placeholder="John Doe">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="simple-login-field">
                    <label for="email" class="simple-login-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="simple-login-input" placeholder="john@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="simple-login-field">
                    <label for="phone_digits" class="simple-login-label">Phone Number</label>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select id="country_code" name="country_code" class="simple-login-input" style="max-width:110px;">
                            <option value="NP" @selected(old('country_code', 'NP') === 'NP')>NP (+977)</option>
                        </select>

                        <input id="phone_digits" type="text" name="phone_digits" value="{{ old('phone_digits') }}"
                            class="simple-login-input" placeholder="9812345678">
                    </div>

                    <small style="color:#6b7280;">Enter only the 10-digit phone number (e.g., 9812345678)</small>
                    <x-input-error :messages="$errors->get('country_code')" class="mt-2" />
                    <x-input-error :messages="$errors->get('phone_digits')" class="mt-2" />
                </div>

                <div class="simple-login-field">
                    <label for="password" class="simple-login-label">Password</label>
                    <input id="password" type="password" name="password"
                        class="simple-login-input" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="simple-login-field">
                    <label for="password_confirmation" class="simple-login-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="simple-login-input" placeholder="••••••••">
                </div>

                <button type="submit" class="simple-login-btn">
                    Register
                </button>

                <p class="simple-register-text">
                    Already registered?
                    <a href="{{ route('login') }}">Sign In</a>
                </p>

                <script>
                    const registerForm = document.querySelector('.simple-login-form');

                    const registerFields = [
                        { name: 'name', label: 'Name' },
                        { name: 'email', label: 'Email' },
                        { name: 'phone_digits', label: 'Phone Number' },
                        { name: 'password', label: 'Password' },
                        { name: 'password_confirmation', label: 'Confirm Password' }
                    ];

                    function showRegisterError(input, message) {
                        input.classList.add('border-red-500');

                        if (!input.parentNode.querySelector('.field-error')) {
                            const error = document.createElement('p');
                            error.className = 'field-error text-red-500 text-sm mt-1';
                            error.textContent = message;
                            input.parentNode.appendChild(error);
                        }
                    }

                    function clearRegisterError(input) {
                        input.classList.remove('border-red-500');

                        const error = input.parentNode.querySelector('.field-error');
                        if (error) {
                            error.remove();
                        }
                    }

                    registerFields.forEach(function(field) {
                        const input = document.querySelector('[name="' + field.name + '"]');

                        if (input) {
                            input.addEventListener('input', function() {
                                if (input.value.trim()) {
                                    clearRegisterError(input);
                                }
                            });

                            input.addEventListener('change', function() {
                                if (input.value.trim()) {
                                    clearRegisterError(input);
                                }
                            });
                        }
                    });

                    registerForm.addEventListener('submit', function(e) {
                        let hasError = false;

                        registerFields.forEach(function(field) {
                            const input = document.querySelector('[name="' + field.name + '"]');

                            if (input && !input.value.trim()) {
                                hasError = true;
                                showRegisterError(input, field.label + ' is required.');
                            } else if (input) {
                                clearRegisterError(input);
                            }
                        });

                        const password = document.querySelector('[name="password"]');
                        const confirmPassword = document.querySelector('[name="password_confirmation"]');

                        if (password.value.trim() && confirmPassword.value.trim() && password.value !== confirmPassword.value) {
                            hasError = true;
                            showRegisterError(confirmPassword, 'Password confirmation does not match.');
                        }

                        if (hasError) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });
                </script>
            </form>
        </div>
    </div>

</body>
</html>