<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Login</title>
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
            <h1 class="simple-login-title">Welcome Back</h1>
            <p class="simple-login-subtitle">Sign in to your account to continue</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="simple-login-form">
                @csrf

                <div class="simple-login-field">
                    <label for="email" class="simple-login-label">Email</label>
                    <input
                        id="email"
                        class="simple-login-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="john@example.com"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="simple-login-field">
                    <label for="password" class="simple-login-label">Password</label>
                    <input
                        id="password"
                        class="simple-login-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="simple-login-row">
                    <label class="simple-remember-wrap">
                        <input id="remember_me" type="checkbox" name="remember" class="simple-checkbox">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="simple-forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="simple-login-btn">
                    Sign In
                </button>

                <p class="simple-register-text">
                    Don't have an account?
                    <a href="{{ route('register') }}">Sign up</a>
                </p>
            </form>
        </div>

        <p class="simple-login-footer">
            By signing in, you agree to our Terms of Service and Privacy Policy
        </p>
    </div>

</body>
</html>