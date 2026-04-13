<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - My Bookings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="home-body">

    <header class="vehicles-navbar">
        <div class="vehicles-nav-left">
            <a href="{{ route('home') }}" class="vehicles-brand-link">
                <img src="{{ asset('images/logo/logo.png') }}" alt="VehicleRent Logo" class="vehicles-brand-logo">
                <span class="vehicles-brand-text">VehicleRent</span>
            </a>
        </div>

        <nav class="vehicles-nav-center">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('user.bookings') }}" class="active">My Bookings</a>
        </nav>

        <div class="vehicles-nav-right">
            <span class="customer-pill">Customer</span>
            <a href="#" class="logout-link">Logout</a>
        </div>
    </header>

    <main class="bookings-page-wrapper">
        <section class="bookings-page-header">
            <h1>My Bookings</h1>
            <p>View and manage your vehicle bookings</p>
        </section>

        <section class="bookings-list">

            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/car1.jpg') }}" alt="Mercedes S-Class">
                </div>

                <div class="booking-content">
                    <div class="booking-top-row">
                        <div>
                            <h3>Mercedes S-Class</h3>
                            <p class="booking-id">Booking ID: b1</p>
                        </div>
                        <span class="booking-status status-confirmed">Confirmed</span>
                    </div>

                    <div class="booking-info-row">
                        <div class="booking-info-item">
                            <span class="booking-label">Pick-up</span>
                            <strong>3/25/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Drop-off</span>
                            <strong>3/28/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Total Price</span>
                            <strong>$450</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Booked On</span>
                            <strong>3/20/2026</strong>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="booking-btn booking-btn-dark">View Details</a>
                        <a href="#" class="booking-btn booking-btn-light">Contact Vendor</a>
                    </div>
                </div>
            </div>

            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/bike1.jpg') }}" alt="Harley Davidson">
                </div>

                <div class="booking-content">
                    <div class="booking-top-row">
                        <div>
                            <h3>Harley Davidson</h3>
                            <p class="booking-id">Booking ID: b2</p>
                        </div>
                        <span class="booking-status status-pending">Pending</span>
                    </div>

                    <div class="booking-info-row">
                        <div class="booking-info-item">
                            <span class="booking-label">Pick-up</span>
                            <strong>4/1/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Drop-off</span>
                            <strong>4/3/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Total Price</span>
                            <strong>$160</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Booked On</span>
                            <strong>3/22/2026</strong>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="booking-btn booking-btn-light">Modify Booking</a>
                        <a href="#" class="booking-btn booking-btn-danger">Cancel Booking</a>
                    </div>
                </div>
            </div>

            <div class="booking-card">
                <div class="booking-image">
                    <img src="{{ asset('images/vehicles/car3.jpg') }}" alt="Range Rover Sport">
                </div>

                <div class="booking-content">
                    <div class="booking-top-row">
                        <div>
                            <h3>Range Rover Sport</h3>
                            <p class="booking-id">Booking ID: b3</p>
                        </div>
                        <span class="booking-status status-completed">Completed</span>
                    </div>

                    <div class="booking-info-row">
                        <div class="booking-info-item">
                            <span class="booking-label">Pick-up</span>
                            <strong>3/15/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Drop-off</span>
                            <strong>3/18/2026</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Total Price</span>
                            <strong>$540</strong>
                        </div>

                        <div class="booking-info-item">
                            <span class="booking-label">Booked On</span>
                            <strong>3/10/2026</strong>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <a href="#" class="booking-btn booking-btn-dark">Book Again</a>
                    </div>
                </div>
            </div>

        </section>
    </main>

</body>
</html>
