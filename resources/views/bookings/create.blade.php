@extends('layouts.app')

@section('title', 'Book Vehicle')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-blue-600 font-semibold hover:underline">
                Back to Vehicle Details
            </a>
        </div>

        @if ($isLoading)
            <div class="mb-6 bg-white border border-gray-200 rounded-xl p-4 text-gray-500">
                Loading booking details...
            </div>
        @elseif ($errorMessage)
            <div class="mb-6 bg-white border border-red-200 rounded-xl p-4 text-red-600">
                {{ $errorMessage }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-white border border-green-200 rounded-xl p-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-white border border-red-200 rounded-xl p-4 text-red-600">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            id="bookingForm"
            method="POST"
            action="{{ $editingBooking ? route('bookings.page.update', $editingBooking) : route('bookings.page.store') }}"
            novalidate
        >
            @csrf
            @if ($editingBooking)
                @method('PATCH')
            @endif
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $editingBooking ? 'Modify Booking' : 'Book Vehicle' }}</h1>
                        <p class="text-gray-500 mt-2">
                            {{ $editingBooking ? 'Update your booking details below.' : 'Complete the booking form below to reserve your selected vehicle.' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Selected Vehicle</h2>

                        <div class="flex flex-col sm:flex-row gap-5">
                            <img
                                src="{{ asset($vehicle->image) }}"
                                alt="{{ $vehicle->name }}"
                                class="w-full sm:w-64 h-44 object-cover rounded-xl border border-gray-200"
                            >

                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $vehicle->name }}</h3>
                                <p class="text-gray-500 mt-1">{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>

                                <div class="flex flex-wrap gap-3 mt-4">
                                    <span class="bg-blue-100 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                                        {{ $vehicle->category }}
                                    </span>

                                    <span class="bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-full">
                                        {{ $vehicle->location }}
                                    </span>

                                    <span class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-full">
                                        RS {{ number_format($vehicle->price_per_day, 0) }}/day
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Rental Dates</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pick-up Date</label>
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ old('start_date', $formDefaults['start_date']) }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Drop-off Date</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ old('end_date', $formDefaults['end_date']) }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Pickup Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
                                <input
                                    type="text"
                                    value="{{ $vehicle->location }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time</label>
                                <select
                                    name="pickup_time"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Select pickup time</option>
                                    @forelse ($pickupTimeSlots as $slot)
                                        <option value="{{ $slot->label }}" @selected(old('pickup_time', $formDefaults['pickup_time']) === $slot->label)>{{ $slot->label }}</option>
                                    @empty
                                        <option value="" disabled>No pickup times available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Customer Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    value="{{ old('full_name', $formDefaults['full_name'] ?? auth()->user()?->name) }}"
                                    placeholder="Enter your full name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $formDefaults['phone'] ?? auth()->user()?->phone) }}"
                                    placeholder="Enter your phone number"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $formDefaults['email'] ?? auth()->user()?->email) }}"
                                    placeholder="Enter your email address"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship / ID Number</label>
                                <input
                                    type="text"
                                    name="citizenship_id"
                                    value="{{ old('citizenship_id', $formDefaults['citizenship_id']) }}"
                                    placeholder="Enter your citizenship or ID number"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Request</label>
                            <textarea
                                name="special_request"
                                rows="4"
                                placeholder="Write any additional request here"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >{{ old('special_request', $formDefaults['special_request']) }}</textarea>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-5">Confirmation Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Selected Vehicle</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->name }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vendor</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->vendor?->name ?? 'Unknown Vendor' }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Pickup City</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->location }}</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-500">Vehicle Type</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ $vehicle->type?->value ?? $vehicle->type }}</p>
                            </div>
                        </div>
                    </div>

                    @unless ($editingBooking)
                        <div
                            id="paymentMethodSection"
                            class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 {{ old('payment_method') ? '' : 'hidden' }}"
                        >
                            <h2 class="text-xl font-semibold text-gray-900 mb-5">Payment Method</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="block border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-400 transition">
                                    <div class="flex items-start gap-3">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="cod"
                                            class="mt-1"
                                            @checked(old('payment_method') === 'cod')
                                        >
                                        <div>
                                            <span class="block font-semibold text-gray-900">Cash on Delivery</span>
                                            <span class="block text-sm text-gray-500 mt-1">Confirm the booking now and pay at pickup.</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="block border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-purple-400 transition">
                                    <div class="flex items-start gap-3">
                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="khalti"
                                            class="mt-1"
                                            @checked(old('payment_method') === 'khalti')
                                        >
                                        <div>
                                            <span class="block font-semibold text-gray-900">Khalti Sandbox</span>
                                            <span class="block text-sm text-gray-500 mt-1">Continue to Khalti sandbox after booking details are saved.</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endunless
                </div>

                <div>
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sticky top-2">
                        <h2 class="text-2xl font-semibold text-gray-900">Booking Summary</h2>
                        <p class="text-gray-500 mt-2">Review your booking details before final confirmation.</p>

                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Vehicle</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->name }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Category</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->category }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Availability</span>
                                <span class="font-semibold text-gray-900">{{ $availabilityLabel }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Location</span>
                                <span class="font-semibold text-gray-900">{{ $vehicle->location }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Rate Per Day</span>
                                <span class="font-semibold text-gray-900">RS {{ number_format($vehicle->price_per_day, 0) }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Estimated Days</span>
                                <span id="estimatedDaysValue" class="font-semibold text-gray-900">{{ $estimatedDays }} days</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="subtotalValue" class="font-semibold text-gray-900">RS {{ number_format($subtotal, 0) }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Service Fee</span>
                                <span class="font-semibold text-gray-900">RS {{ number_format($serviceFee, 0) }}</span>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-semibold text-gray-900">Total Price</span>
                                <span id="totalPriceValue" class="text-2xl font-bold text-blue-600">RS {{ number_format($total, 0) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <button
                                type="button"
                                id="proceedToPaymentButton"
                                class="block w-full text-center bg-black text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition {{ old('payment_method') ? 'hidden' : '' }}"
                            >
                                Proceed to Payment
                            </button>

                            <button
                                type="submit"
                                form="bookingForm"
                                id="confirmBookingButton"
                                class="block w-full text-center bg-black text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition {{ old('payment_method') || $editingBooking ? '' : 'hidden' }}"
                                @unless (old('payment_method') || $editingBooking) disabled @endunless
                            >
                                Confirm Booking
                            </button>

                            <a
                                href="{{ route('vehicles.show', $vehicle) }}"
                                class="block w-full text-center bg-white border border-gray-300 text-gray-700 py-4 rounded-xl font-semibold hover:bg-gray-50 transition"
                            >
                                Back to Details
                            </a>
                        </div>

                        <p class="text-xs text-gray-400 mt-5 leading-6">
                            Choose cash on delivery to submit the booking, or Khalti Sandbox to pay online before confirmation.
                        </p>
                    </div>
                </div>

            </div>

            <div
                id="bookingPricingData"
                data-default-estimated-days="{{ $estimatedDays }}"
                data-price-per-day="{{ (float) $vehicle->price_per_day }}"
                data-service-fee="{{ (float) $serviceFee }}"
            ></div>

            <script>
const bookingForm = document.getElementById('bookingForm');
const pricingData = document.getElementById('bookingPricingData');
const startDateInput = document.querySelector('[name="start_date"]');
const endDateInput = document.querySelector('[name="end_date"]');
const estimatedDaysValue = document.getElementById('estimatedDaysValue');
const subtotalValue = document.getElementById('subtotalValue');
const totalPriceValue = document.getElementById('totalPriceValue');
const paymentMethodSection = document.getElementById('paymentMethodSection');
const proceedToPaymentButton = document.getElementById('proceedToPaymentButton');
const confirmBookingButton = document.getElementById('confirmBookingButton');

const fields = [
    { name: 'start_date', label: 'Pick-up Date' },
    { name: 'end_date', label: 'Drop-off Date' },
    { name: 'pickup_time', label: 'Pickup Time' },
    { name: 'full_name', label: 'Full Name' },
    { name: 'phone', label: 'Phone Number' },
    { name: 'email', label: 'Email Address' },
    { name: 'citizenship_id', label: 'Citizenship / ID Number' },
    { name: 'special_request', label: 'Special Request' }
];

function showError(input, label) {
    input.classList.add('border-red-500');

    if (!input.parentNode.querySelector('.field-error')) {
        const err = document.createElement('p');
        err.className = 'field-error text-red-500 text-sm mt-1';
        err.textContent = label + ' is required.';
        input.parentNode.appendChild(err);
    }
}

function clearError(input) {
    input.classList.remove('border-red-500');
    const err = input.parentNode.querySelector('.field-error');
    if (err) {
        err.remove();
    }
}

function parseDateInput(value) {
    if (!value) {
        return null;
    }

    const [year, month, day] = value.split('-').map(Number);

    if (!year || !month || !day) {
        return null;
    }

    return new Date(year, month - 1, day);
}

function getSelectedDays() {
    const startDate = parseDateInput(startDateInput?.value);
    const endDate = parseDateInput(endDateInput?.value);

    if (!startDate || !endDate) {
        return null;
    }

    const millisecondsPerDay = 24 * 60 * 60 * 1000;
    const rawDays = Math.floor((endDate - startDate) / millisecondsPerDay);

    return Math.max(1, rawDays);
}

function formatCurrency(value) {
    return 'RS ' + new Intl.NumberFormat('en-US').format(Math.round(value));
}

function updateBookingSummary() {
    if (!pricingData || !estimatedDaysValue || !subtotalValue || !totalPriceValue) {
        return;
    }

    const defaultEstimatedDays = Number(pricingData.dataset.defaultEstimatedDays) || 1;
    const pricePerDay = Number(pricingData.dataset.pricePerDay) || 0;
    const serviceFee = Number(pricingData.dataset.serviceFee) || 0;
    const selectedDays = getSelectedDays();
    const estimatedDays = selectedDays ?? defaultEstimatedDays;
    const subtotal = pricePerDay * estimatedDays;
    const totalPrice = subtotal + serviceFee;

    estimatedDaysValue.textContent = estimatedDays + ' days';
    subtotalValue.textContent = formatCurrency(subtotal);
    totalPriceValue.textContent = formatCurrency(totalPrice);
}

function selectedPaymentMethod() {
    const checkedPaymentMethod = document.querySelector('[name="payment_method"]:checked');

    return checkedPaymentMethod ? checkedPaymentMethod.value : null;
}

function showPaymentMethods() {
    if (!paymentMethodSection || !proceedToPaymentButton || !confirmBookingButton) {
        return;
    }

    paymentMethodSection.classList.remove('hidden');
    proceedToPaymentButton.classList.add('hidden');
    confirmBookingButton.classList.remove('hidden');
    confirmBookingButton.disabled = selectedPaymentMethod() === null;
    paymentMethodSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function updatePaymentButtonState() {
    if (!confirmBookingButton) {
        return;
    }

    confirmBookingButton.disabled = selectedPaymentMethod() === null;
}

fields.forEach(function(field) {
    const input = document.querySelector('[name="' + field.name + '"]');

    if (input) {
        input.addEventListener('input', function() {
            if (input.value.trim()) {
                clearError(input);
            }
        });

        input.addEventListener('change', function() {
            if (input.value.trim()) {
                clearError(input);
            }
        });
    }
});

if (startDateInput) {
    startDateInput.addEventListener('change', updateBookingSummary);
    startDateInput.addEventListener('input', updateBookingSummary);
}

if (endDateInput) {
    endDateInput.addEventListener('change', updateBookingSummary);
    endDateInput.addEventListener('input', updateBookingSummary);
}

updateBookingSummary();
if (proceedToPaymentButton) {
    proceedToPaymentButton.addEventListener('click', showPaymentMethods);
}

document.querySelectorAll('[name="payment_method"]').forEach(function(input) {
    input.addEventListener('change', updatePaymentButtonState);
});
updatePaymentButtonState();

bookingForm.addEventListener('submit', function(e) {
    let hasError = false;

    fields.forEach(function(field) {
        const input = document.querySelector('[name="' + field.name + '"]');

        if (input && !input.value.trim()) {
            hasError = true;
            showError(input, field.label);
        } else if (input) {
            clearError(input);
        }
    });

    if (hasError) {
        e.preventDefault();
        e.stopPropagation();

        const firstError = document.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return false;
    }

    if (paymentMethodSection && paymentMethodSection.classList.contains('hidden')) {
        e.preventDefault();
        e.stopPropagation();
        showPaymentMethods();

        return false;
    }

    if (selectedPaymentMethod() === null) {
        e.preventDefault();
        e.stopPropagation();
        showPaymentMethods();

        return false;
    }

    if (selectedPaymentMethod() === 'cod' && !window.confirm('Confirm this cash on delivery booking?')) {
        e.preventDefault();
        e.stopPropagation();

        return false;
    }
});
</script>
        </form>
    </div>
</div>
@endsection
