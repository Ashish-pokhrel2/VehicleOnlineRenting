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
            <!-- Loading, error, and empty states -->
            @if ($isLoading)
                <div class="booking-card">
                    <div class="booking-content">
                        <p class="text-gray-500">Loading bookings...</p>
                    </div>
                </div>
            @elseif ($errorMessage)
                <div class="booking-card">
                    <div class="booking-content">
                        <p class="text-red-600">{{ $errorMessage }}</p>
                    </div>
                </div>
            @else
                @forelse ($bookings as $booking)
                    @php
                        $statusLabel = $booking->status->value ?? (string) $booking->status;
                        $statusClass = $statusClasses[$statusLabel] ?? 'status-pending';
                    @endphp
                    <div class="booking-card">
                        <div class="booking-image">
                            <img src="{{ asset($booking->vehicle?->image) }}" alt="{{ $booking->vehicle?->name }}">
                        </div>

                        <div class="booking-content">
                            <div class="booking-top-row">
                                <div>
                                    <h3>{{ $booking->vehicle?->name }}</h3>
                                    <p class="booking-id">Booking ID: b{{ $booking->id }}</p>
                                </div>
                                <span class="booking-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>

                            <div class="booking-info-row">
                                <div class="booking-info-item">
                                    <span class="booking-label">Pick-up</span>
                                    <strong>{{ $booking->start_date?->format('n/j/Y') }}</strong>
                                </div>

                                <div class="booking-info-item">
                                    <span class="booking-label">Drop-off</span>
                                    <strong>{{ $booking->end_date?->format('n/j/Y') }}</strong>
                                </div>

                                <div class="booking-info-item">
                                    <span class="booking-label">Total Price</span>
                                    <strong>${{ $booking->total_price }}</strong>
                                </div>

                                <div class="booking-info-item">
                                    <span class="booking-label">Booked On</span>
                                    <strong>{{ $booking->created_at?->format('n/j/Y') }}</strong>
                                </div>
                            </div>

                            <div class="booking-actions">
                                @if ($statusLabel === 'Confirmed')
                                    <a href="#" class="booking-btn booking-btn-dark">View Details</a>
                                    <a href="#" class="booking-btn booking-btn-light">Contact Vendor</a>
                                @elseif ($statusLabel === 'Pending')
                                    <a href="#" class="booking-btn booking-btn-light">Modify Booking</a>
                                    <a href="#" class="booking-btn booking-btn-danger">Cancel Booking</a>
                                @elseif ($statusLabel === 'Completed')
                                    <a href="#" class="booking-btn booking-btn-dark">Book Again</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="booking-card">
                        <div class="booking-content">
                            <p class="text-gray-500">No bookings available yet.</p>
                        </div>
                    </div>
                @endforelse
            @endif

        </section>
    </main>

</body>
</html>
