<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Vehicles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

    <header class="vehicles-navbar">
        <div class="vehicles-nav-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="vehicles-brand-logo">
            </a>
        </div>

        <nav class="vehicles-nav-center">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('vehicles.index') }}" class="active">Vehicles</a>
            <a href="#">Vendors</a>
            <a href="{{ route('bookings.index') }}">My Bookings</a>
        </nav>

        <div class="vehicles-nav-right">
            <span class="customer-pill">👤 Customer</span>
            <a href="#" class="logout-link">↪ Logout</a>
        </div>
    </header>

    <main class="vehicles-page-wrapper">
        <section class="vehicles-page-header">
            <h1>Available Vehicles</h1>
            <p>8 vehicles found</p>
        </section>

        <section class="vehicles-layout">
            <aside class="vehicles-filter-card">
                <h3>☰ Filters</h3>

                <div class="filter-group">
                    <h4>Price Range</h4>

                    <div class="price-range-line">
                        <div class="range-dot left-dot"></div>
                        <div class="range-dot right-dot"></div>
                    </div>

                    <div class="price-labels">
                        <span>$0</span>
                        <span>$300</span>
                    </div>
                </div>

                <div class="filter-group">
                    <h4>Vehicle Type</h4>

                    <label class="filter-check"><input type="checkbox"> Car</label>
                    <label class="filter-check"><input type="checkbox"> Bike</label>
                    <label class="filter-check"><input type="checkbox"> Scooter</label>
                    <label class="filter-check"><input type="checkbox"> Van</label>
                </div>

                <div class="filter-group">
                    <h4>Category</h4>

                    <label class="filter-check"><input type="checkbox"> Luxury</label>
                    <label class="filter-check"><input type="checkbox"> Sports</label>
                    <label class="filter-check"><input type="checkbox"> SUV</label>
                    <label class="filter-check"><input type="checkbox"> Cruiser</label>
                    <label class="filter-check"><input type="checkbox"> Electric</label>
                    <label class="filter-check"><input type="checkbox"> Sedan</label>
                    <label class="filter-check"><input type="checkbox"> Convertible</label>
                    <label class="filter-check"><input type="checkbox"> Family</label>
                </div>

                <button class="reset-filter-btn">Reset Filters</button>
            </aside>

            <section class="vehicles-grid-page">
                <div class="vehicle-card page-card">
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
                            <span class="rating">⭐ 4.9</span>
                        </div>
                        <small>156 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 5</span>
                            <span>⚙️ Automatic</span>
                            <span>⛽ Hybrid</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$150<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
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
                            <span class="rating">⭐ 5</span>
                        </div>
                        <small>89 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 2</span>
                            <span>⚙️ Manual</span>
                            <span>⛽ Gasoline</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$300<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
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
                            <span class="rating">⭐ 4.8</span>
                        </div>
                        <small>124 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 7</span>
                            <span>⚙️ Automatic</span>
                            <span>⛽ Diesel</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$180<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
                    <div class="vehicle-image-wrapper">
                        <img src="{{ asset('images/vehicles/bike1.jpg') }}" alt="Harley Davidson">
                        <span class="vehicle-tag">Cruiser</span>
                    </div>
                    <div class="vehicle-content">
                        <div class="vehicle-header">
                            <div>
                                <h3>Harley Davidson</h3>
                                <p>Elite Motors</p>
                            </div>
                            <span class="rating">⭐ 4.7</span>
                        </div>
                        <small>67 reviews</small>
                        <div class="vehicle-meta">
                            <span>⚙️ Manual</span>
                            <span>⛽ Gasoline</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$80<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
                    <div class="vehicle-image-wrapper">
                        <img src="{{ asset('images/vehicles/scooter.jpg') }}" alt="Electric Scooter">
                        <span class="vehicle-tag">Electric</span>
                    </div>
                    <div class="vehicle-content">
                        <div class="vehicle-header">
                            <div>
                                <h3>Electric Scooter</h3>
                                <p>Urban Mobility</p>
                            </div>
                            <span class="rating">⭐ 4.5</span>
                        </div>
                        <small>234 reviews</small>
                        <div class="vehicle-meta">
                            <span>🔋 Electric</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$25<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
                    <div class="vehicle-image-wrapper">
                        <img src="{{ asset('images/vehicles/car4.jpg') }}" alt="Honda Accord">
                        <span class="vehicle-tag">Sedan</span>
                    </div>
                    <div class="vehicle-content">
                        <div class="vehicle-header">
                            <div>
                                <h3>Honda Accord</h3>
                                <p>Urban Mobility</p>
                            </div>
                            <span class="rating">⭐ 4.6</span>
                        </div>
                        <small>198 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 5</span>
                            <span>⚙️ Automatic</span>
                            <span>⛽ Gasoline</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$60<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
                    <div class="vehicle-image-wrapper">
                        <img src="{{ asset('images/vehicles/bmw.jpg') }}" alt="BMW Convertible">
                        <span class="vehicle-tag">Convertible</span>
                    </div>
                    <div class="vehicle-content">
                        <div class="vehicle-header">
                            <div>
                                <h3>BMW Convertible</h3>
                                <p>Premium Rentals</p>
                            </div>
                            <span class="rating">⭐ 4.9</span>
                        </div>
                        <small>92 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 4</span>
                            <span>⚙️ Automatic</span>
                            <span>⛽ Gasoline</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$220<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>

                <div class="vehicle-card page-card">
                    <div class="vehicle-image-wrapper">
                        <img src="{{ asset('images/vehicles/van.jpg') }}" alt="Family Van">
                        <span class="vehicle-tag">Family</span>
                    </div>
                    <div class="vehicle-content">
                        <div class="vehicle-header">
                            <div>
                                <h3>Family Van</h3>
                                <p>Elite Motors</p>
                            </div>
                            <span class="rating">⭐ 4.7</span>
                        </div>
                        <small>145 reviews</small>
                        <div class="vehicle-meta">
                            <span>👥 8</span>
                            <span>⚙️ Automatic</span>
                            <span>⛽ Diesel</span>
                        </div>
                        <div class="vehicle-footer">
                            <div class="price">$90<span>/day</span></div>
                            <a href="#">View Details →</a>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

</body>
</html>