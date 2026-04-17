<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - Vehicles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

    <!-- ===================== Navbar ===================== -->
    <header class="vehicles-navbar">
        <div class="vehicles-nav-left">
            <a href="{{ route('home') }}" class="vehicles-brand-link">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="vehicles-brand-logo">
                <span class="vehicles-brand-text">VehicleRent</span>
            </a>
        </div>

        <nav class="vehicles-nav-center">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('vehicles.index') }}" class="active">Vehicles</a>
            <a href="{{ route('user.bookings') }}">My Bookings</a>
        </nav>

        <div class="vehicles-nav-right">
            <span class="customer-pill">Customer</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
            <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.querySelector('form').submit();">Logout</a>
        </div>
    </header>

    <!-- ===================== Main Content ===================== -->
    <main class="vehicles-page-wrapper">

        <!-- Page Header -->
        <section class="vehicles-page-header">
            <h1>Available Vehicles</h1>
            <p>{{ $vehicles->count() }} vehicles found</p>
        </section>

        <section class="vehicles-layout">

            <!-- ===================== Filters Sidebar ===================== -->
            <aside class="vehicles-filter-card">
                <h3>Filters</h3>

                <div class="filter-group">
                    <h4>Price Range</h4>
                    <div class="price-range-line">
                        <div class="range-dot left-dot"></div>
                        <div class="range-dot right-dot"></div>
                    </div>
                    <div class="price-labels">
                        <span>${{ $priceRange['min'] }}</span>
                        <span>${{ $priceRange['max'] }}</span>
                    </div>
                </div>

                <div class="filter-group">
                    <h4>Vehicle Type</h4>
                    @forelse ($types as $type)
                        <label class="filter-check"><input type="checkbox"> {{ $type }}</label>
                    @empty
                        <p class="text-sm text-gray-500">No types available</p>
                    @endforelse
                </div>

                <div class="filter-group">
                    <h4>Category</h4>
                    @forelse ($categories as $category)
                        <label class="filter-check"><input type="checkbox"> {{ $category }}</label>
                    @empty
                        <p class="text-sm text-gray-500">No categories available</p>
                    @endforelse
                </div>

                <button class="reset-filter-btn">Reset Filters</button>
            </aside>

            <!-- ===================== Vehicles Grid ===================== -->
            <section class="vehicles-grid-page">
                <!-- Loading, error, and empty states -->
                @if ($isLoading)
                    <div class="vehicle-card page-card">
                        <div class="vehicle-content">
                            <p class="text-gray-500">Loading vehicles...</p>
                        </div>
                    </div>
                @elseif ($errorMessage)
                    <div class="vehicle-card page-card">
                        <div class="vehicle-content">
                            <p class="text-red-600">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @else
                    @forelse ($vehicles as $vehicle)
                        <div class="vehicle-card page-card">
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
                        <div class="vehicle-card page-card">
                            <div class="vehicle-content">
                                <p class="text-gray-500">No vehicles available right now.</p>
                            </div>
                        </div>
                    @endforelse
                @endif

            </section>
        </section>
    </main>

</body>
</html>
