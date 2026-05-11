@extends('layouts.vendor')

@section('content')

<div class="max-w-[980px] mx-auto">

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-900">Booking Requests</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $bookings->count() }} total bookings</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($bookings as $booking)
            @php
                $status = $booking->status->value ?? (string) $booking->status;

                $statusColors = [
                    'Pending' => 'bg-yellow-100 text-yellow-700',
                    'Confirmed' => 'bg-green-100 text-green-700',
                    'Cancelled' => 'bg-red-100 text-red-700',
                    'Completed' => 'bg-blue-100 text-blue-700',
                ];

                $customerName = $booking->customer->name ?? $booking->full_name ?? 'Customer';
                $customerEmail = $booking->email ?? $booking->customer->email ?? '';
                $vehicleName = $booking->vehicle->name ?? 'Vehicle not found';
                $vehicleImage = $booking->vehicle->image ?? 'images/vehicles/car1.jpg';
            @endphp

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row gap-4">

                    <img src="{{ asset($vehicleImage) }}"
                         alt="{{ $vehicleName }}"
                         class="w-full sm:w-28 h-36 sm:h-24 rounded-xl object-cover bg-gray-100">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="text-lg font-bold text-gray-900 break-words">{{ $vehicleName }}</h2>
                                <p class="text-xs text-gray-500 mt-1">
                                    Booking ID: BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>

                            <span class="shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
                            <div class="flex items-start gap-2 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="hidden sm:block w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a8.25 8.25 0 0115 0" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-[11px] text-gray-400">Customer</p>
                                    <p class="text-xs font-medium text-gray-700 break-words">{{ $customerName }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="hidden sm:block w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4.5 11h15M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-[11px] text-gray-400">Pick-up</p>
                                    <p class="text-xs font-medium text-gray-700 whitespace-nowrap">
                                        {{ $booking->start_date?->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="hidden sm:block w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4.5 11h15M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-[11px] text-gray-400">Drop-off</p>
                                    <p class="text-xs font-medium text-gray-700 whitespace-nowrap">
                                        {{ $booking->end_date?->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <p class="text-[11px] text-gray-400">Total</p>
                                <p class="text-sm font-semibold text-blue-600 whitespace-nowrap">
                                    Rs. {{ number_format($booking->total_price, 0) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 mt-5">
                            <button type="button"
                                data-id="BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}"
                                data-vehicle="{{ $vehicleName }}"
                                data-customer="{{ $customerName }}"
                                data-email="{{ $customerEmail }}"
                                data-phone="{{ $booking->phone ?? 'Not provided' }}"
                                data-pickup="{{ $booking->start_date?->format('d M Y') }}"
                                data-dropoff="{{ $booking->end_date?->format('d M Y') }}"
                                data-time="{{ $booking->pickup_time ?? 'Not provided' }}"
                                data-price="Rs. {{ number_format($booking->total_price, 0) }}"
                                data-status="{{ $status }}"
                                data-request="{{ $booking->special_request ?? 'No special request' }}"
                                onclick="openBookingModalFromButton(this)"
                                class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                View Details
                            </button>

                            @if($customerEmail)
                                <a href="mailto:{{ $customerEmail }}"
                                   class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Contact Customer
                                </a>
                            @else
                                <button type="button"
                                        onclick="alert('Customer email is not available.')"
                                        class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-400">
                                    Contact Customer
                                </button>
                            @endif

                            @if($status === 'Pending')
                                <form method="POST" action="{{ route('vendor.bookings.confirm', $booking) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-green-600 text-xs font-semibold text-white hover:bg-green-700">
                                        Confirm
                                    </button>
                                </form>

                                <form id="reject-form-{{ $booking->id }}"
                                      method="POST"
                                      action="{{ route('vendor.bookings.reject', $booking) }}">
                                    @csrf
                                    @method('PATCH')
                                </form>

                                <button type="button"
                                        onclick="openRejectModal({{ $booking->id }})"
                                        class="px-4 py-2 rounded-lg bg-red-600 text-xs font-semibold text-white hover:bg-red-700">
                                    Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center shadow-sm">
                <p class="text-sm text-gray-400">No bookings found</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
        <div class="flex items-start justify-between border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Booking Details</h2>
                <p id="modalBookingId" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <button type="button" onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-700">
                ✕
            </button>
        </div>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400">Vehicle</p>
                <p id="modalVehicle" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Status</p>
                <p id="modalStatus" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Customer</p>
                <p id="modalCustomer" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Email</p>
                <p id="modalEmail" class="font-semibold text-gray-900 break-all"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Phone</p>
                <p id="modalPhone" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Pickup Time</p>
                <p id="modalTime" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Pick-up</p>
                <p id="modalPickup" class="font-semibold text-gray-900"></p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Drop-off</p>
                <p id="modalDropoff" class="font-semibold text-gray-900"></p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-gray-400">Total Price</p>
                <p id="modalPrice" class="font-bold text-blue-600"></p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-gray-400">Special Request</p>
                <p id="modalRequest" class="font-medium text-gray-700"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button"
                    onclick="closeBookingModal()"
                    class="px-5 py-2 rounded-lg bg-gray-900 text-sm font-semibold text-white hover:bg-gray-800">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>

            <div class="flex-1">
                <h2 class="text-base font-bold text-gray-900">Reject Booking?</h2>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    This will update the booking status to Cancelled. You can still view it later in the booking history.
                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button"
                    onclick="closeRejectModal()"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Keep Booking
            </button>

            <button type="button"
                    onclick="submitReject()"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Reject
            </button>
        </div>
    </div>
</div>

<script>
    let selectedRejectBookingId = null;

    function openBookingModalFromButton(button) {
        openBookingModal({
            id: button.dataset.id,
            vehicle: button.dataset.vehicle,
            customer: button.dataset.customer,
            email: button.dataset.email,
            phone: button.dataset.phone,
            pickup: button.dataset.pickup,
            dropoff: button.dataset.dropoff,
            time: button.dataset.time,
            price: button.dataset.price,
            status: button.dataset.status,
            request: button.dataset.request
        });
    }

    function openBookingModal(data) {
        document.getElementById('modalBookingId').textContent = data.id;
        document.getElementById('modalVehicle').textContent = data.vehicle;
        document.getElementById('modalCustomer').textContent = data.customer;
        document.getElementById('modalEmail').textContent = data.email || 'Not provided';
        document.getElementById('modalPhone').textContent = data.phone;
        document.getElementById('modalPickup').textContent = data.pickup;
        document.getElementById('modalDropoff').textContent = data.dropoff;
        document.getElementById('modalTime').textContent = data.time;
        document.getElementById('modalPrice').textContent = data.price;
        document.getElementById('modalStatus').textContent = data.status;
        document.getElementById('modalRequest').textContent = data.request;

        const modal = document.getElementById('bookingModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBookingModal() {
        const modal = document.getElementById('bookingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openRejectModal(bookingId) {
        selectedRejectBookingId = bookingId;

        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        selectedRejectBookingId = null;

        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function submitReject() {
        if (!selectedRejectBookingId) {
            return;
        }

        const form = document.getElementById('reject-form-' + selectedRejectBookingId);

        if (form) {
            form.submit();
        }
    }
</script>

@endsection