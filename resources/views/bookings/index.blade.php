<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VehicleRent - My Bookings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="home-body">

@include('partials.navbar')

<script>
async function cancelBooking(event, bookingId) {
    event.preventDefault();
    
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        const response = await fetch(`/bookings/${bookingId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
        });
        
        const text = await response.text();
        
        if (!response.ok) {
            throw new Error(`Failed to cancel booking (Status: ${response.status})`);
        }
        
        const data = JSON.parse(text);
        
        if (data.success) {
            alert('Booking cancelled successfully');
            location.reload();
        }
    } catch (error) {
        alert('Failed to cancel booking: ' + error.message);
    }
}
</script>

<main class="bookings-page-wrapper">
    <section class="bookings-page-header">
        <h1>My Bookings</h1>
        <p>View and manage your vehicle bookings</p>
    </section>

    <section class="bookings-list">
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
                                <p class="booking-id">
                                    Booking ID: BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>

                            <span class="booking-status {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
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
                                <strong>Rs. {{ number_format($booking->total_price, 0) }}</strong>
                            </div>

                            <div class="booking-info-item">
                                <span class="booking-label">Booked On</span>
                                <strong>{{ $booking->created_at?->format('n/j/Y') }}</strong>
                            </div>
                        </div>

                        <div class="booking-actions">
                            @if ($statusLabel === 'Confirmed')
                                <a href="{{ route('vehicles.show', $booking->vehicle) }}" class="booking-btn booking-btn-dark">
                                    View Details
                                </a>

                                <button
                                    type="button"
                                    class="booking-btn booking-btn-light"
                                    onclick="alert('Vendor contact feature will be available in the next update.')"
                                >
                                    Contact Vendor
                                </button>

                            @elseif ($statusLabel === 'Pending')
                                <a href="{{ route('bookings.edit', $booking) }}" class="booking-btn booking-btn-light">
                                    Modify Booking
                                </a>

                                <button
                                    type="button"
                                    class="booking-btn booking-btn-danger"
                                    onclick="cancelBooking(event, {{ $booking->id }})"
                                >
                                    Cancel Booking
                                </button>

                            @elseif ($statusLabel === 'Completed')
                                <a href="{{ route('bookings.create', $booking->vehicle) }}" class="booking-btn booking-btn-dark">
                                    Book Again
                                </a>
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
