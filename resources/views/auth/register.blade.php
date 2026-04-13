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
        <!-- Logo -->
        <div class="simple-login-logo-wrap">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="simple-login-logo">
            </a>
        </div>

        <!-- Register Card -->
        <div class="simple-login-card">
            <h1 class="simple-login-title">Create an Account</h1>
            <p class="simple-login-subtitle">Join VehicleRent and start booking today</p>

            <form method="POST" action="{{ route('register') }}" class="simple-login-form">
                @csrf

                <!-- Name -->
                <div class="simple-login-field">
                    <label for="name" class="simple-login-label">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        required autofocus class="simple-login-input" placeholder="John Doe">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="simple-login-field">
                    <label for="email" class="simple-login-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        required class="simple-login-input" placeholder="john@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div class="simple-login-field">
                    <label for="phone" class="simple-login-label">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                        required class="simple-login-input" placeholder="9812345678">
                    <small style="color:#6b7280;">Enter only the 10-digit phone number (e.g., 9812345678)</small>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Register As -->
                <div class="simple-login-field">
                    <label for="role" class="simple-login-label">Register As</label>
                    <select id="role" name="role" required class="simple-login-input">
                        <option value="">Select role</option>
                        <option value="customer">Customer</option>
                        <option value="vendor">Vendor</option>
                        <option value="admin">Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="simple-login-field">
                    <label for="password" class="simple-login-label">Password</label>
                    <input id="password" type="password" name="password"
                        required class="simple-login-input" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="simple-login-field">
                    <label for="password_confirmation" class="simple-login-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        required class="simple-login-input" placeholder="••••••••">
                </div>

                <!-- Register Button -->
                <button type="submit" class="simple-login-btn">
                    Register
                </button>

                <!-- Login Link -->
                <p class="simple-register-text">
                    Already registered?
                    <a href="{{ route('login') }}">Sign In</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>