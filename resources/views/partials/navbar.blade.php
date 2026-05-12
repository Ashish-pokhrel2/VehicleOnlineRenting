@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp

<header class="navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="brand-logo">
            </a>
        </div>

        <nav class="nav-center desktop-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                Home
            </a>

            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                Vehicles
            </a>

            @if (! $isAdmin)
                <a href="{{ route('user.bookings') }}" class="{{ request()->routeIs('user.bookings') ? 'active' : '' }}">
                    My Bookings
                </a>
            @endif

            @if (! $isAdmin)
    <a href="{{ route('customer.contact') }}" class="{{ request()->routeIs('customer.contact') ? 'active' : '' }}">
        Contact
    </a>
  @endif
        </nav>

        <div class="nav-right desktop-actions">
            @auth
                <a href="{{ route('profile.edit') }}" class="customer-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>{{ $isAdmin ? 'Admin' : 'Customer' }}</span>
                </a>
        
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                        </svg>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
                <a href="{{ route('register') }}" class="signup-btn">Sign Up</a>
            @endauth
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn" type="button">☰</button>
    </header>
