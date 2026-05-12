<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

    <!-- ===================== NAVBAR ===================== -->
@include('partials.navbar')

    <!-- ===================== MOBILE MENU ===================== -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-inner">
            <div class="mobile-menu-top">
                <span class="mobile-menu-title">VehicleRent</span>
                <button class="mobile-close-btn" id="mobileCloseBtn" type="button">✕</button>
            </div>
            <nav class="mobile-nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('vehicles.index') }}">Vehicles</a>
                <a href="{{ route('user.bookings') }}">My Bookings</a>
            </nav>
            <div class="mobile-auth-links">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-signup-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-login-link">Login</a>
                    <a href="{{ route('register') }}" class="mobile-signup-btn">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- ===================== HERO SECTION ===================== -->
<section class="hero" style="background-image: linear-gradient(rgba(25,55,175,0.45), rgba(25,55,175,0.45)), url('{{ asset('images/vehicles/hero-bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Find Your Perfect Ride</h1>
                <p>Rent premium vehicles at the best prices. Easy booking, flexible options.</p>

                <form id="searchForm" class="search-box" novalidate>
                    <div class="search-item search-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-input-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <input type="text" id="location" name="location" placeholder="Location">
                    </div>
                    <div class="search-item search-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-input-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <input type="date" id="date" name="date">
                    </div>
                    <div class="search-item search-item-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-input-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        <input type="text" id="vehicle_type" name="vehicle_type" placeholder="Vehicle Type">
                    </div>
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Search
                    </button>
                </form>

                <div id="searchMessage" style="margin-top: 18px; color: white; font-weight: 500;"></div>
                <div id="searchResults" style="margin-top: 24px; width: 100%;"></div>
            </div>
        </div>
    </section>

    <!-- ===================== CATEGORY SECTION ===================== -->
    <section class="section white-section">
        <h2>Browse by Category</h2>
        <p class="section-subtitle">Choose from our wide range of vehicles</p>
        <div class="category-grid">
            @if ($isLoading)
                <div class="category-card"><div class="category-icon"></div><h3>Loading</h3><p>Fetching categories...</p></div>
            @elseif ($errorMessage)
                <div class="category-card"><div class="category-icon"></div><h3>Unavailable</h3><p>{{ $errorMessage }}</p></div>
            @else
                @forelse ($typeCounts as $typeCount)
                  @php
$typeParam = strtolower($typeCount['label']);

if ($typeParam === 'cars') {
    $typeParam = 'car';
} elseif ($typeParam === 'bikes') {
    $typeParam = 'bike';
} elseif ($typeParam === 'scooters') {
    $typeParam = 'scooter';
} elseif ($typeParam === 'buses') {
    $typeParam = 'bus';
}
@endphp

<a href="{{ route('vehicles.index', ['type' => $typeParam]) }}" class="category-card category-card-link">
                        <div class="category-icon-wrap">
                      @php $label = strtolower($typeCount['label']); @endphp

@if($label === 'cars' || $label === 'car')
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b5bdb" stroke-width="1.8" width="32" height="32">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5c.3-.8 1-1.3 1.9-1.3h10.2c.9 0 1.6.5 1.9 1.3l2 5"></path>
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13h14a2 2 0 0 1 2 2v3h-2"></path>
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 18H3v-3a2 2 0 0 1 2-2"></path>
        <circle cx="7" cy="18" r="2"></circle>
        <circle cx="17" cy="18" r="2"></circle>
    </svg>

@elseif($label === 'bikes' || $label === 'bike')
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b5bdb" stroke-width="1.8" width="32" height="32">
        <circle cx="5.5" cy="17.5" r="2.5"/>
        <circle cx="18.5" cy="17.5" r="2.5"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17.5l4-8h4l3.5 8"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M11 9.5h4"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9.5l2-3h2"/>
    </svg>

@elseif($label === 'scooters' || $label === 'scooter')
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b5bdb" stroke-width="1.8" width="32" height="32">
        <circle cx="6" cy="17" r="2"/>
        <circle cx="18" cy="17" r="2"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 17h9l2-5h-5l-2-3h-3"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h3"/>
    </svg>

@elseif($label === 'buses' || $label === 'bus')
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b5bdb" stroke-width="1.8" width="32" height="32">
        <rect x="3" y="6" width="18" height="10" rx="2"></rect>
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9h12"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12h12"/>
        <circle cx="7" cy="18" r="2"></circle>
        <circle cx="17" cy="18" r="2"></circle>
    </svg>

@else
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b5bdb" stroke-width="1.8" width="32" height="32">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5c.3-.8 1-1.3 1.9-1.3h10.2c.9 0 1.6.5 1.9 1.3l2 5"></path>
        <circle cx="7" cy="18" r="2"></circle>
        <circle cx="17" cy="18" r="2"></circle>
    </svg>
@endif
                        </div>
                        <h3>{{ $typeCount['label'] }}</h3>
                        <p>{{ $typeCount['count'] }} vehicles available</p>
                    </a>
                @empty
                    <div class="category-card"><div class="category-icon"></div><h3>No categories</h3><p>Vehicles will appear here once available</p></div>
                @endforelse
            @endif
        </div>
    </section>

    <!-- ===================== FEATURED VEHICLES ===================== -->
    <section class="section light-section">
        <h2>Featured Vehicles</h2>
        <p class="section-subtitle">Popular choices from our collection</p>
        <div class="vehicles-grid">
            @if ($isLoading)
                <div class="vehicle-card"><div class="vehicle-content"><p>Loading...</p></div></div>
            @elseif ($errorMessage)
                <div class="vehicle-card"><div class="vehicle-content"><p>{{ $errorMessage }}</p></div></div>
            @else
                @forelse ($featuredVehicles as $vehicle)
                    <div class="vehicle-card" onclick="window.location='{{ route('vehicles.show', $vehicle) }}'">
                        <div class="vehicle-image-wrapper">
                            <img src="{{ asset($vehicle->image) }}" alt="{{ $vehicle->name }}">
                            <span class="vehicle-tag">{{ $vehicle->category }}</span>
                        </div>
                        <div class="vehicle-content">
                            <div class="vehicle-header">
                                <div>
                                    <h3>{{ $vehicle->name }}</h3>
                                    <p>{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>
                                </div>
                                <span class="rating">{{ number_format($vehicle->rating, 1) }}</span>
                            </div>
                            <small>{{ $vehicle->reviews }} reviews</small>
                            <div class="vehicle-meta">
                                <span>{{ $vehicle->seats }}</span>
                                <span>{{ $vehicle->transmission }}</span>
                                <span>{{ $vehicle->fuel }}</span>
                            </div>
                            <div class="vehicle-footer">
                            <div class="price">RS {{ number_format($vehicle->price_per_day, 0) }}<span>/day</span></div>
                                <a href="{{ route('vehicles.show', $vehicle) }}" onclick="event.stopPropagation();">View Details →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="vehicle-card"><div class="vehicle-content"><p>No featured vehicles available.</p></div></div>
                @endforelse
            @endif
        </div>
        <div class="center-btn">
            <a href="{{ route('vehicles.index') }}"><button class="dark-btn">View All Vehicles</button></a>
        </div>
    </section>

    <!-- ===================== WHY CHOOSE US ===================== -->
    <section class="section white-section">
        <h2>Why Choose Us</h2>
        <p class="section-subtitle">We make vehicle rental simple and hassle-free</p>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b5bdb" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                </div>
                <h3>Safe & Secure</h3>
                <p>All vehicles are fully insured and regularly maintained</p>
            </div>
            <div class="why-card">
                <div class="why-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b5bdb" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3>24/7 Support</h3>
                <p>Our customer support team is always here to help</p>
            </div>
            <div class="why-card">
                <div class="why-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#3b5bdb" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                </div>
                <h3>Best Rates</h3>
                <p>Competitive pricing with no hidden fees</p>
            </div>
        </div>
    </section>

    <!-- ===================== CTA SECTION ===================== -->
    <section class="cta-section">
        <h2>Ready to Hit the Road?</h2>
        <p>Join thousands of satisfied customers and book your perfect vehicle today</p>
        <a href="{{ route('vehicles.index') }}"><button class="rent-btn">Rent Now</button></a>
    </section>

    <!-- ===================== FOOTER ===================== -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <div class="footer-logo-box">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="footer-icon-only">
                    </div>
                    <span class="footer-brand-text">VehicleRent</span>
                </div>
                <p>Your trusted partner for vehicle rentals</p>
            </div>
            <div>
                <h4>Quick Links</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('vehicles.index') }}">Vehicles</a>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('user.bookings') }}">My Bookings</a>
            </div>
            <div>
                <h4>Support</h4>
                <a href="#">Help Center</a>
                <a href="#">Contact Us</a>
                <a href="#">FAQs</a>
            </div>
            <div>
                <h4>Contact</h4>
                <p>support@vehiclerent.com</p>
                <p>+1 (555) 123-4567</p>
                <p>24/7 Customer Support</p>
            </div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} VehicleRent. All rights reserved.</div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        function openMobileMenu() { mobileMenu.classList.add('show-mobile-menu'); mobileMenuOverlay.classList.add('show-mobile-overlay'); }
        function closeMobileMenu() { mobileMenu.classList.remove('show-mobile-menu'); mobileMenuOverlay.classList.remove('show-mobile-overlay'); }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
        if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeMobileMenu);
        if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);

        const searchForm = document.getElementById('searchForm');
        const searchMessage = document.getElementById('searchMessage');
        const searchResults = document.getElementById('searchResults');

      if (searchForm) {
        const locationInput = document.getElementById('location');
const dateInput = document.getElementById('date');
const vehicleTypeInput = document.getElementById('vehicle_type');

function clearHomeSearchError() {
    if (locationInput.value.trim()) {
        locationInput.style.border = '1px solid #e5e7eb';
    }

    if (dateInput.value.trim()) {
        dateInput.style.border = '1px solid #e5e7eb';
    }

    if (vehicleTypeInput.value.trim()) {
        vehicleTypeInput.style.border = '1px solid #e5e7eb';
    }

    if (
        locationInput.value.trim() &&
        dateInput.value.trim() &&
        vehicleTypeInput.value.trim()
    ) {
        searchMessage.innerHTML = '';
        searchMessage.style.background = 'transparent';
        searchMessage.style.padding = '0';
        searchMessage.style.display = 'block';
    }
}

[locationInput, dateInput, vehicleTypeInput].forEach(field => {
    field.addEventListener('input', clearHomeSearchError);
    field.addEventListener('change', clearHomeSearchError);
});
    searchForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const location = document.getElementById('location');
        const date = document.getElementById('date');
        const vehicleType = document.getElementById('vehicle_type');

        let hasError = false;

        [location, date, vehicleType].forEach(field => {
            field.style.border = '1px solid #e5e7eb';
        });

        if (!location.value.trim()) {
            location.style.border = '2px solid #ef4444';
            hasError = true;
        }

        if (!date.value.trim()) {
            date.style.border = '2px solid #ef4444';
            hasError = true;
        }

        if (!vehicleType.value.trim()) {
            vehicleType.style.border = '2px solid #ef4444';
            hasError = true;
        }

        if (hasError) {
            searchMessage.innerHTML = 'Please fill location, date and vehicle type before searching.';
            searchMessage.style.color = '#ffffff';
            searchMessage.style.background = '#dc2626';
            searchMessage.style.padding = '12px 20px';
            searchMessage.style.borderRadius = '12px';
            searchMessage.style.display = 'inline-block';
            searchResults.innerHTML = '';
            return;
        }

        searchMessage.innerHTML = 'Searching...';
        searchMessage.style.background = 'transparent';
        searchResults.innerHTML = '';

        try {
            const params = new URLSearchParams({
                location: location.value.trim(),
                date: date.value,
                vehicle_type: vehicleType.value.trim()
            });

            const response = await fetch(`{{ route('vehicles.search.ajax') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) throw new Error(data.message || 'Search failed.');

            searchMessage.innerHTML = '';

            if (!data.vehicles || data.vehicles.length === 0) {
                searchResults.innerHTML = `<div style="background:#fff;padding:20px;border-radius:16px;color:#111;max-width:1100px;margin:0 auto;"><p style="margin:0;font-weight:600;">No vehicles found.</p></div>`;
                return;
            }

            let html = `<div style="background:#fff;padding:24px;border-radius:20px;color:#111;max-width:1100px;margin:0 auto;"><h3 style="margin-bottom:20px;">Search Results</h3><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,420px));justify-content:center;gap:20px;">`;

            data.vehicles.forEach(vehicle => {
                html += `<div style="border:1px solid #ddd;border-radius:16px;overflow:hidden;background:#fff;">
                    <img src="${vehicle.image_url}" alt="${vehicle.name}" style="width:100%;height:220px;object-fit:cover;">
                    <div style="padding:16px;text-align:left;">
                        <h4 style="margin:0 0 12px 0;text-align:center;">${vehicle.name}</h4>
                        <p style="margin:0 0 10px 0;color:#111;"><strong>Type:</strong> ${vehicle.category ?? vehicle.type ?? 'N/A'}</p>
                        <p style="margin:0 0 10px 0;color:#111;"><strong>Location:</strong> ${vehicle.location ?? 'N/A'}</p>
                        <p style="margin:0 0 16px 0;color:#111;"><strong>Price:</strong> RS ${Number(vehicle.price_per_day).toLocaleString('en-US')}/day</p>
                        <div style="text-align:center;">
                            <a href="/vehicles/${vehicle.id}" style="display:inline-block;padding:10px 16px;background:#020826;color:#fff;text-decoration:none;border-radius:10px;">View Details</a>
                        </div>
                    </div>
                </div>`;
            });

            html += `</div></div>`;
            searchResults.innerHTML = html;

        } catch (error) {
            searchMessage.innerHTML = '';
            searchResults.innerHTML = `<div style="background:#fff;padding:20px;border-radius:16px;color:red;max-width:1100px;margin:0 auto;">Something went wrong while searching.</div>`;
        }
    });
}
    </script>
</body>
</html>