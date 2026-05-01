<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="simple-login-page">
    @php
        $selectedRole = $role ?? \App\Enums\UserRole::CUSTOMER;
        $selectedRoleValue = $selectedRole->value;
        $selectedLoginRoute = $loginRoute ?? 'login';
    @endphp

    <div class="simple-login-wrapper">
        <div class="simple-login-logo-wrap">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="simple-login-logo">
            </a>
        </div>

        <div class="simple-login-card">
            <h1 class="simple-login-title">Welcome Back</h1>
            <p class="simple-login-subtitle">Sign in as {{ ucfirst($selectedRoleValue) }} to continue</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="simple-login-row" style="margin-bottom: 1rem; gap: 0.75rem;">
                <a class="simple-login-btn" style="flex: 1; text-align: center; background: {{ $selectedRoleValue === 'customer' ? '#2563eb' : '#eef2ff' }}; color: {{ $selectedRoleValue === 'customer' ? '#fff' : '#475569' }};" href="{{ route('login') }}">Customer</a>
                <a class="simple-login-btn" style="flex: 1; text-align: center; background: {{ $selectedRoleValue === 'vendor' ? '#2563eb' : '#eef2ff' }}; color: {{ $selectedRoleValue === 'vendor' ? '#fff' : '#475569' }};" href="{{ route('vendor.login') }}">Vendor</a>
                <a class="simple-login-btn" style="flex: 1; text-align: center; background: {{ $selectedRoleValue === 'admin' ? '#2563eb' : '#eef2ff' }}; color: {{ $selectedRoleValue === 'admin' ? '#fff' : '#475569' }};" href="{{ route('admin.login') }}">Admin</a>
            </div>

          <form method="POST" action="{{ route($selectedLoginRoute) }}" class="simple-login-form" novalidate>
                @csrf
                <input type="hidden" name="role" value="{{ $selectedRoleValue }}">

                <div class="simple-login-field">
                    <label for="email" class="simple-login-label">Email</label>
                    <input
                        id="email"
                        class="simple-login-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
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
                        autocomplete="new-password"
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
              <script>
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');

function clearLoginFieldError(input) {
    input.classList.remove('border-red-500');

    const fieldBox = input.closest('.simple-login-field');

    fieldBox.querySelectorAll('.field-error').forEach(el => el.remove());
    fieldBox.querySelectorAll('.text-red-500').forEach(el => el.remove());
    fieldBox.querySelectorAll('.text-red-600').forEach(el => el.remove());
    fieldBox.querySelectorAll('ul').forEach(el => el.remove());
}

[emailInput, passwordInput].forEach(function(input) {
    input.addEventListener('input', function() {
        if (input.value.trim() !== '') {
            clearLoginFieldError(input);
        }
    });

    input.addEventListener('change', function() {
        if (input.value.trim() !== '') {
            clearLoginFieldError(input);
        }
    });
});
</script>
            </form>
        </div>

        <p class="simple-login-footer">
            By signing in, you agree to our Terms of Service and Privacy Policy
        </p>
    </div>

</body>
</html>