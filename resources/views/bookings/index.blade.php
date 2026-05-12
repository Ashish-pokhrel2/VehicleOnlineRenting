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

<main class="bookings-page-wrapper">
    <section class="bookings-page-header">
        <h1>My Bookings</h1>
        <p>View and manage your vehicle bookings</p>
    </section>

    <div id="bookingMessage" class="mx-auto mb-4 hidden max-w-[1500px] rounded-xl px-5 py-3 text-sm font-semibold"></div>

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

                    $statusClasses = [
                        'Pending' => 'bg-yellow-100 text-yellow-700',
                        'Confirmed' => 'bg-green-100 text-green-700',
                        'Cancelled' => 'bg-red-100 text-red-700',
                        'Completed' => 'bg-blue-100 text-blue-700',
                    ];

                    $statusClass = $statusClasses[$statusLabel] ?? 'bg-gray-100 text-gray-600';

                    $vendorEmail = $booking->vehicle?->vendor?->email ?? '';
                    $vehicleName = $booking->vehicle?->name ?? 'Vehicle';
                    $bookingCode = 'BK-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
                    $mailSubject = rawurlencode('Question about booking ' . $bookingCode);
                    $mailBody = rawurlencode(
                        "Hello,\n\nI would like to ask about my booking.\n\n" .
                        "Booking ID: {$bookingCode}\n" .
                        "Vehicle: {$vehicleName}\n" .
                        "Pick-up: " . optional($booking->start_date)->format('n/j/Y') . "\n" .
                        "Drop-off: " . optional($booking->end_date)->format('n/j/Y') . "\n\n" .
                        "Thank you."
                    );
                @endphp

                <div class="booking-card" id="booking-card-{{ $booking->id }}">
                    <div class="booking-image">
                        <img src="{{ asset($booking->vehicle?->image) }}" alt="{{ $booking->vehicle?->name }}">
                    </div>

                    <div class="booking-content">
                        <div class="booking-top-row">
                            <div>
                                <h3>{{ $booking->vehicle?->name }}</h3>
                                <p class="booking-id">
                                    Booking ID: {{ $bookingCode }}
                                </p>
                            </div>

                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
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
                                <strong>RS {{ number_format($booking->total_price, 0) }}</strong>
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

                                @if ($vendorEmail)
                                    <a href="mailto:{{ $vendorEmail }}?subject={{ $mailSubject }}&body={{ $mailBody }}"
                                       class="booking-btn booking-btn-light">
                                        Contact Vendor
                                    </a>
                                @else
                                    <button
                                        type="button"
                                        class="booking-btn booking-btn-light"
                                        onclick="showBookingMessage('Vendor email is not available for this booking.', 'error')"
                                    >
                                        Contact Vendor
                                    </button>
                                @endif

                            @elseif ($statusLabel === 'Pending')
                                <a href="{{ route('bookings.edit', $booking) }}" class="booking-btn booking-btn-light">
                                    Modify Booking
                                </a>

                                <button
                                    type="button"
                                    class="booking-btn booking-btn-danger"
                                    onclick="openCancelBookingModal({{ $booking->id }}, '{{ $booking->vehicle?->name }}', '{{ $bookingCode }}')"
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

<!-- Cancel Booking Modal -->
<div id="cancelBookingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-900">Cancel Booking?</h2>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Are you sure you want to cancel
                    <span id="cancelBookingName" class="font-semibold text-gray-900"></span>
                    <span id="cancelBookingId" class="font-semibold text-gray-900"></span>?
                    This will update the booking status to Cancelled.
                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button
                type="button"
                onclick="closeCancelBookingModal()"
                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Keep Booking
            </button>

            <button
                type="button"
                onclick="confirmCancelBooking()"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
            >
                Yes, Cancel
            </button>
        </div>
    </div>
</div>

<script>
    let selectedBookingId = null;

    function showBookingMessage(message, type = 'success') {
        const messageBox = document.getElementById('bookingMessage');

        messageBox.textContent = message;
        messageBox.classList.remove(
            'hidden',
            'bg-green-50',
            'text-green-700',
            'border-green-200',
            'bg-red-50',
            'text-red-700',
            'border-red-200',
            'bg-blue-50',
            'text-blue-700',
            'border-blue-200'
        );

        messageBox.classList.add('border');

        if (type === 'error') {
            messageBox.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
        } else if (type === 'info') {
            messageBox.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
        } else {
            messageBox.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
        }

        setTimeout(() => {
            messageBox.classList.add('hidden');
        }, 3500);
    }

    function openCancelBookingModal(bookingId, vehicleName, bookingCode) {
        selectedBookingId = bookingId;

        document.getElementById('cancelBookingName').textContent = vehicleName || 'this booking';
        document.getElementById('cancelBookingId').textContent = bookingCode ? '(' + bookingCode + ')' : '';

        const modal = document.getElementById('cancelBookingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCancelBookingModal() {
        const modal = document.getElementById('cancelBookingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        selectedBookingId = null;
    }

    async function confirmCancelBooking() {
        if (!selectedBookingId) {
            return;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch(`/bookings/${selectedBookingId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error(`Failed to cancel booking. Status: ${response.status}`);
            }

            closeCancelBookingModal();
            showBookingMessage('Booking cancelled successfully.', 'success');

            setTimeout(() => {
                location.reload();
            }, 700);

        } catch (error) {
            closeCancelBookingModal();
            showBookingMessage(error.message || 'Failed to cancel booking.', 'error');
        }
    }
</script>

</body>
</html>