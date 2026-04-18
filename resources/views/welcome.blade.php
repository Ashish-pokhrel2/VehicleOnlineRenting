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
    <header class="navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="brand-logo">
            </a>
        </div>

        <nav class="nav-center desktop-nav">
            <a href="{{ route('home') }}" class="active">Home</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('user.bookings') }}">My Bookings</a>
        </nav>

        <div class="nav-right desktop-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="login-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="signup-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
                <a href="{{ route('register') }}" class="signup-btn">Sign Up</a>
            @endauth
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn" type="button">☰</button>
    </header>

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
                    <a href="{{ route('dashboard') }}" class="mobile-dashboard-link">Dashboard</a>
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
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Find Your Perfect Ride</h1>
                <p>
                    Rent premium vehicles at the best prices. Easy booking,
                    <br>flexible options.
                </p>

                <!-- Updated Search Form -->
                <form id="searchForm" class="search-box">
                    <div class="search-item">
                        <input type="text" id="location" name="location" placeholder="Location">
                    </div>

                    <div class="search-item">
                        <input type="date" id="date" name="date">
                    </div>

                    <div class="search-item">
                        <input type="text" id="vehicle_type" name="vehicle_type" placeholder="Vehicles Type">
                    </div>

                    <button type="submit" class="search-btn">Search</button>
                </form>

                <!-- Search Messages -->
                <div id="searchMessage" style="margin-top: 18px; color: white; font-weight: 500;"></div>

                <!-- Search Results -->
                <div id="searchResults" style="margin-top: 24px; width: 100%;"></div>
            </div>
        </div>
    </section>

    <!-- ===================== CATEGORY SECTION ===================== -->
    <section class="section white-section">
        <h2>Browse by Category</h2>
        <p class="section-subtitle">Choose from our wide range of vehicles</p>

        <div class="category-grid">
            <!-- Loading, error, and empty states -->
            @if ($isLoading)
                <div class="category-card">
                    <div class="category-icon"></div>
                    <h3>Loading</h3>
                    <p>Fetching categories...</p>
                </div>
            @elseif ($errorMessage)
                <div class="category-card">
                    <div class="category-icon"></div>
                    <h3>Unavailable</h3>
                    <p>{{ $errorMessage }}</p>
                </div>
            @else
                @forelse ($typeCounts as $typeCount)
                    <div class="category-card">
                        <div class="category-icon"></div>
                        <h3>{{ $typeCount['label'] }}</h3>
                        <p>{{ $typeCount['count'] }} vehicles available</p>
                    </div>
                @empty
                    <div class="category-card">
                        <div class="category-icon"></div>
                        <h3>No categories</h3>
                        <p>Vehicles will appear here once available</p>
                    </div>
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
                <div class="vehicle-card">
                    <div class="vehicle-content">
                        <p class="text-gray-500">Loading featured vehicles...</p>
                    </div>
                </div>
            @elseif ($errorMessage)
                <div class="vehicle-card">
                    <div class="vehicle-content">
                        <p class="text-red-600">{{ $errorMessage }}</p>
                    </div>
                </div>
            @else
                @forelse ($featuredVehicles as $vehicle)
                    <div class="vehicle-card">
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
                                <div class="price">${{ $vehicle->price_per_day }}<span>/day</span></div>
                                <a href="{{ route('vehicles.show', $vehicle) }}">View Details →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="vehicle-card">
                        <div class="vehicle-content">
                            <p class="text-gray-500">No featured vehicles available.</p>
                        </div>
                    </div>
                @endforelse
            @endif
        </div>

        <div class="center-btn">
            <a href="{{ route('vehicles.index') }}">
                <button class="dark-btn">View All Vehicles</button>
            </a>
        </div>
    </section>

    <!-- ===================== WHY CHOOSE US ===================== -->
    <section class="section white-section">
        <h2>Why Choose Us</h2>
        <p class="section-subtitle">We make vehicle rental simple and hassle-free</p>

        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"></div>
                <h3>Safe & Secure</h3>
                <p>All vehicles are fully insured and regularly maintained</p>
            </div>

            <div class="why-card">
                <div class="why-icon"></div>
                <h3>24/7 Support</h3>
                <p>Our customer support team is always here to help</p>
            </div>

            <div class="why-card">
                <div class="why-icon"></div>
                <h3>Best Rates</h3>
                <p>Competitive pricing with no hidden fees</p>
            </div>
        </div>
    </section>

    <!-- ===================== CTA SECTION ===================== -->
    <section class="cta-section">
        <h2>Ready to Hit the Road?</h2>
        <p>Join thousands of satisfied customers and book your perfect vehicle today</p>
        <a href="{{ route('vehicles.index') }}">
            <button class="rent-btn">Rent Now</button>
        </a>
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

        <div class="footer-bottom">
            © {{ date('Y') }} VehicleRent. All rights reserved.
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        function openMobileMenu() {
            mobileMenu.classList.add('show-mobile-menu');
            mobileMenuOverlay.classList.add('show-mobile-overlay');
        }

        function closeMobileMenu() {
            mobileMenu.classList.remove('show-mobile-menu');
            mobileMenuOverlay.classList.remove('show-mobile-overlay');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }

        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', closeMobileMenu);
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', closeMobileMenu);
        }

        // AJAX Search
        const searchForm = document.getElementById('searchForm');
        const searchMessage = document.getElementById('searchMessage');
        const searchResults = document.getElementById('searchResults');

        if (searchForm) {
            searchForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const location = document.getElementById('location').value.trim();
                const date = document.getElementById('date').value;
                const vehicleType = document.getElementById('vehicle_type').value.trim();

                searchMessage.innerHTML = 'Searching...';
                searchResults.innerHTML = '';

                try {
                    const params = new URLSearchParams({
                        location: location,
                        date: date,
                        vehicle_type: vehicleType
                    });

                    const response = await fetch(`{{ route('vehicles.search.ajax') }}?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Search failed.');
                    }

                    searchMessage.innerHTML = '';

                    if (!data.vehicles || data.vehicles.length === 0) {
                        searchResults.innerHTML = `
                            <div style="background:#fff; padding:20px; border-radius:16px; color:#111; max-width:1100px; margin:0 auto;">
                                <p style="margin:0; font-weight:600;">No vehicles found.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = `
                        <div style="background:#fff; padding:24px; border-radius:20px; color:#111; max-width:1100px; margin:0 auto;">
                            <h3 style="margin-bottom:20px;">Search Results</h3>
                            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 420px)); justify-content:center; gap:20px;">
                    `;

                   data.vehicles.forEach(vehicle => {
    html += `
        <div style="border:1px solid #ddd; border-radius:16px; overflow:hidden; background:#fff;">
            <img src="${vehicle.image_url}" alt="${vehicle.name}" style="width:100%; height:220px; object-fit:cover;">
            <div style="padding:16px; text-align:left;">
                <h4 style="margin:0 0 12px 0; text-align:center;">${vehicle.name}</h4>
                <p style="margin:0 0 10px 0; color:#111;"><strong>Type:</strong> ${vehicle.category ?? vehicle.type ?? 'N/A'}</p>
                <p style="margin:0 0 10px 0; color:#111;"><strong>Location:</strong> ${vehicle.location ?? 'N/A'}</p>
                <p style="margin:0 0 16px 0; color:#111;"><strong>Price:</strong> $${vehicle.price_per_day}/day</p>
                <div style="text-align:center;">
                    <a href="/vehicles/${vehicle.id}" style="display:inline-block; padding:10px 16px; background:#020826; color:#fff; text-decoration:none; border-radius:10px;">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    `;
});

                    html += `</div></div>`;
                    searchResults.innerHTML = html;
                } catch (error) {
                    searchMessage.innerHTML = '';
                    searchResults.innerHTML = `
                        <div style="background:#fff; padding:20px; border-radius:16px; color:red; max-width:1100px; margin:0 auto;">
                            Something went wrong while searching.
                        </div>
                    `;
                    console.error(error);
                }
            });
        }
    </script>

</body>
</html>