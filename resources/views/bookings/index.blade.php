<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleRent - My Bookings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="home-body">

@include('partials.navbar')

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
                                    onclick="openCancelBookingModal(this, '{{ $booking->vehicle?->name }}', 'BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}')"
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

<div id="cancelBookingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h2 class="text-2xl font-bold text-gray-900">Cancel Booking?</h2>

        <p class="mt-3 text-sm leading-6 text-gray-600">
            Are you sure you want to cancel
            <span id="cancelBookingName" class="font-semibold text-gray-900"></span>
            <span id="cancelBookingId" class="font-semibold text-gray-900"></span>?
            This booking will be removed from your visible list.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button
                type="button"
                onclick="closeCancelBookingModal()"
                class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Keep Booking
            </button>

            <button
                type="button"
                onclick="confirmCancelBooking()"
                class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
            >
                Yes, Cancel
            </button>
        </div>
    </div>
</div>

<script>
    let selectedBookingCard = null;

    function openCancelBookingModal(button, vehicleName, bookingId) {
        selectedBookingCard = button.closest('.booking-card');

        document.getElementById('cancelBookingName').textContent = vehicleName || 'this booking';
        document.getElementById('cancelBookingId').textContent = bookingId ? '(' + bookingId + ')' : '';

        const modal = document.getElementById('cancelBookingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCancelBookingModal() {
        const modal = document.getElementById('cancelBookingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        selectedBookingCard = null;
    }

    function confirmCancelBooking() {
        if (selectedBookingCard) {
            selectedBookingCard.style.display = 'none';
        }

        closeCancelBookingModal();
    }
</script>

</body>
</html>