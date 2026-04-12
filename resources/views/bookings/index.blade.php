<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - My Bookings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

    <!-- ===================== NAVBAR ===================== -->
    <header class="vehicles-navbar">
        <div class="vehicles-nav-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="vehicles-brand-logo">
            </a>
        </div>

        <nav class="vehicles-nav-center">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="#">Vendors</a>
            <a href="{{ route('bookings.index') }}" class="active">My Bookings</a>
        </nav>

        <div class="vehicles-nav-right">
            @auth
                <span class="customer-pill">👤 {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-link">↪ Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="logout-link">Login</a>
            @endauth
        </div>
    </header>

    <!-- ===================== PAGE CONTENT ===================== -->
    <main class="vehicles-page-wrapper">
        <section class="vehicles-page-header">
            <h1>My Bookings</h1>
            <p>View and manage your vehicle bookings</p>
        </section>

        <section class="bookings-container">

            <!-- Booking Card 1 -->
            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/car1.jpg') }}" alt="Mercedes S-Class">
                </div>

                <div class="booking-details">
                    <div class="booking-header">
                        <div>
                            <h3>Mercedes S-Class</h3>
                            <p class="booking-id">Booking ID: b1</p>
                        </div>
                        <span class="status-badge confirmed">Confirmed</span>
                    </div>

                    <div class="booking-meta">
                        <div><strong>Pick-up:</strong> 3/25/2026</div>
                        <div><strong>Drop-off:</strong> 3/28/2026</div>
                        <div><strong>Total Price:</strong> $450</div>
                        <div><strong>Booked On:</strong> 3/20/2026</div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="btn-primary">View Details</a>
                        <a href="#" class="btn-secondary">Contact Vendor</a>
                    </div>
                </div>
            </div>

            <!-- Booking Card 2 -->
            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/bike1.jpg') }}" alt="Harley Davidson">
                </div>

                <div class="booking-details">
                    <div class="booking-header">
                        <div>
                            <h3>Harley Davidson</h3>
                            <p class="booking-id">Booking ID: b2</p>
                        </div>
                        <span class="status-badge pending">Pending</span>
                    </div>

                    <div class="booking-meta">
                        <div><strong>Pick-up:</strong> 4/1/2026</div>
                        <div><strong>Drop-off:</strong> 4/3/2026</div>
                        <div><strong>Total Price:</strong> $160</div>
                        <div><strong>Booked On:</strong> 3/22/2026</div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="btn-secondary">Modify Booking</a>
                        <a href="#" class="btn-danger">Cancel Booking</a>
                    </div>
                </div>
            </div>

            <!-- Booking Card 3 -->
            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/car3.jpg') }}" alt="Range Rover Sport">
                </div>

                <div class="booking-details">
                    <div class="booking-header">
                        <div>
                            <h3>Range Rover Sport</h3>
                            <p class="booking-id">Booking ID: b3</p>
                        </div>
                        <span class="status-badge completed">Completed</span>
                    </div>

                    <div class="booking-meta">
                        <div><strong>Pick-up:</strong> 3/15/2026</div>
                        <div><strong>Drop-off:</strong> 3/18/2026</div>
                        <div><strong>Total Price:</strong> $540</div>
                        <div><strong>Booked On:</strong> 3/10/2026</div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="btn-primary">Book Again</a>
                    </div>
                </div>
            </div>

        </section>
    </main>

</body>
</html>