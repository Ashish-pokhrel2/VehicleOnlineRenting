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

        <nav class="nav-center">
            <a href="{{ route('home') }}" class="active">Home</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('user.bookings') }}">My Bookings</a>
        </nav>

        <div class="nav-right">
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
    </header>

    <!-- ===================== HERO SECTION ===================== -->
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1>Find Your Perfect Ride</h1>
                <p>
                    Rent premium vehicles at the best prices. Easy booking,
                    <br>flexible options.
                </p>

                <div class="search-box">
                    <div class="search-item">
                        <input type="text" placeholder="Location">
                    </div>

                    <div class="search-item">
                        <input type="text" placeholder="mm/dd/year">
                    </div>

                    <div class="search-item">
                        <input type="text" placeholder="Vehicles Type">
                    </div>

                    <button class="search-btn">Search</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== CATEGORY SECTION ===================== -->
    <section class="section white-section">
        <h2>Browse by Category</h2>
        <p class="section-subtitle">Choose from our wide range of vehicles</p>

        <div class="category-grid">
            <div class="category-card">
                <div class="category-icon"></div>
                <h3>Cars</h3>
                <p>5 vehicles available</p>
            </div>

            <div class="category-card">
                <div class="category-icon"></div>
                <h3>Bikes</h3>
                <p>1 vehicles available</p>
            </div>
        </div>
    </section>

    <!-- ===================== FEATURED VEHICLES ===================== -->
    <section class="section light-section">
        <h2>Featured Vehicles</h2>
        <p class="section-subtitle">Popular choices from our collection</p>

        <div class="vehicles-grid">
            <!-- Mercedes -->
            <div class="vehicle-card">
                <div class="vehicle-image-wrapper">
                    <img src="{{ asset('images/vehicles/car1.jpg') }}" alt="Mercedes S-Class">
                    <span class="vehicle-tag">Luxury</span>
                </div>
                <div class="vehicle-content">
                    <div class="vehicle-header">
                        <div>
                            <h3>Mercedes S-Class</h3>
                            <p>Premium Rentals</p>
                        </div>
                        <span class="rating">4.9</span>
                    </div>
                    <small>156 reviews</small>
                    <div class="vehicle-meta">
                        <span>5</span>
                        <span>Automatic</span>
                        <span>Hybrid</span>
                    </div>
                    <div class="vehicle-footer">
                        <div class="price">$150<span>/day</span></div>
                        <a href="{{ route('vehicles.show', 1) }}">View Details →</a>
                    </div>
                </div>
            </div>

            <!-- Porsche -->
            <div class="vehicle-card">
                <div class="vehicle-image-wrapper">
                    <img src="{{ asset('images/vehicles/car2.jpg') }}" alt="Porsche 911">
                    <span class="vehicle-tag">Sports</span>
                </div>
                <div class="vehicle-content">
                    <div class="vehicle-header">
                        <div>
                            <h3>Porsche 911</h3>
                            <p>Premium Rentals</p>
                        </div>
                        <span class="rating">5</span>
                    </div>
                    <small>89 reviews</small>
                    <div class="vehicle-meta">
                        <span>2</span>
                        <span>Manual</span>
                        <span>Gasoline</span>
                    </div>
                    <div class="vehicle-footer">
                        <div class="price">$300<span>/day</span></div>
                        <a href="{{ route('vehicles.show', 2) }}">View Details →</a>
                    </div>
                </div>
            </div>

            <!-- Range Rover -->
            <div class="vehicle-card">
                <div class="vehicle-image-wrapper">
                    <img src="{{ asset('images/vehicles/car3.jpg') }}" alt="Range Rover Sport">
                    <span class="vehicle-tag">SUV</span>
                </div>
                <div class="vehicle-content">
                    <div class="vehicle-header">
                        <div>
                            <h3>Range Rover Sport</h3>
                            <p>Elite Motors</p>
                        </div>
                        <span class="rating">4.8</span>
                    </div>
                    <small>124 reviews</small>
                    <div class="vehicle-meta">
                        <span>7</span>
                        <span>Automatic</span>
                        <span>Diesel</span>
                    </div>
                    <div class="vehicle-footer">
                        <div class="price">$180<span>/day</span></div>
                        <a href="{{ route('vehicles.show', 3) }}">View Details →</a>
                    </div>
                </div>
            </div>
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

</body>
</html>