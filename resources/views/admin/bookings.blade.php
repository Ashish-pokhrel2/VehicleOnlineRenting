@extends('layouts.admin')

@section('content')
    @php
        $statusStyles = [
            'Confirmed' => ['bg' => '#DCFCE7', 'text' => '#016630'],
            'Pending' => ['bg' => '#FEF9C2', 'text' => '#894B00'],
            'Completed' => ['bg' => '#DBEAFE', 'text' => '#193CB8'],
            'Cancelled' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
        ];
    @endphp

    <section class="flex flex-col items-start gap-6">
        {{-- Header + Search --}}
        <div class="flex w-full items-start justify-between">
            <div class="flex flex-col items-start gap-2">
                <h1 class="text-[30px] font-bold leading-9" style="color: #101828;">Booking Management</h1>
                <p class="text-base leading-6" style="color: #4A5565;">{{ $totalBookings }} total bookings</p>
            </div>

            <form method="GET" action="{{ route('admin.bookings') }}" class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5" style="color: #99A1AF;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" stroke-width="2" />
                        <path d="M20 20l-4.35-4.35" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search bookings..."
                    class="h-9 w-64 rounded-lg border-0 pl-10 pr-3 text-sm outline-none focus:ring-2 focus:ring-blue-500"
                    style="background: #F3F3F5; color: #717182;"
                >
            </form>
        </div>

        {{-- Table Container --}}
        <div class="w-full overflow-hidden rounded-[14px] border border-black/10 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead style="background: #F9FAFB;">
                        <tr class="border-b border-black/10">
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Booking ID</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Vehicle</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Customer</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Vendor</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Start Date</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">End Date</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Amount</th>
                            <th class="px-6 py-4 text-sm font-semibold leading-5" style="color: #364153;">Status</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold leading-5" style="color: #364153;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            @php
                                $status = $booking->status->value;
                                $style = $statusStyles[$status] ?? ['bg' => '#F3F4F6', 'text' => '#374151'];
                                $customerEmail = $booking->customer?->email;
                                $vendorEmail = $booking->vendor?->email;
                                $payment = $booking->latestPayment;
                                $settlement = $booking->latestSettlement;
                                $canReleaseSettlement = $payment
                                    && $payment->status === \App\Enums\BookingPaymentStatus::COMPLETED
                                    && (! $settlement || $settlement->status !== \App\Enums\BookingSettlementStatus::RELEASED);
                            @endphp
                            <tr class="border-b border-black/10 last:border-none">
                                <td class="px-6 py-6 text-sm font-medium leading-5" style="color: #101828;">
                                    b{{ $booking->id }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ asset($booking->vehicle?->image ?? 'images/logo/logo.png') }}"
                                            alt="{{ $booking->vehicle?->name ?? 'Vehicle' }}"
                                            class="h-12 w-12 rounded object-cover"
                                        >
                                        <span class="text-sm font-medium leading-5" style="color: #101828;">
                                            {{ $booking->vehicle?->name ?? 'Unknown Vehicle' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-normal leading-5" style="color: #364153;">
                                    {{ $booking->customer?->name ?? 'Unknown Customer' }}
                                </td>
                                <td class="px-6 py-6 text-sm font-normal leading-5" style="color: #364153;">
                                    {{ $booking->vendor?->name ?? 'Unknown Vendor' }}
                                </td>
                                <td class="px-6 py-6 text-sm font-normal leading-5" style="color: #364153;">
                                    {{ optional($booking->start_date)->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-6 text-sm font-normal leading-5" style="color: #364153;">
                                    {{ optional($booking->end_date)->format('n/j/Y') }}
                                </td>
                                <td class="px-6 py-6 text-sm font-semibold leading-5" style="color: #101828;">
                                    RS {{ number_format((float) $booking->total_price, 0) }}
                                </td>
                                <td class="px-6 py-6">
                                    <span
                                        class="inline-block rounded-lg px-2 py-1 text-xs font-medium leading-4"
                                        style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};"
                                    >
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-end">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button
                                                    type="button"
                                                    class="inline-flex h-8 w-9 items-center justify-center rounded-lg transition hover:bg-slate-100"
                                                    aria-label="Actions"
                                                >
                                                    <svg class="h-4 w-4" style="color: #0A0A0A;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="6" r="1.5" fill="currentColor" stroke="none" />
                                                        <circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none" />
                                                        <circle cx="12" cy="18" r="1.5" fill="currentColor" stroke="none" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                @if ($booking->vehicle)
                                                    <x-dropdown-link href="{{ route('vehicles.show', $booking->vehicle) }}">
                                                        View vehicle
                                                    </x-dropdown-link>
                                                @else
                                                    <span class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-400">
                                                        View vehicle
                                                    </span>
                                                @endif

                                                @if ($booking->vendor)
                                                    <x-dropdown-link href="{{ route('admin.vendors.show', $booking->vendor) }}">
                                                        View vendor
                                                    </x-dropdown-link>
                                                @else
                                                    <span class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-400">
                                                        View vendor
                                                    </span>
                                                @endif

                                                @if ($customerEmail)
                                                    <x-dropdown-link href="mailto:{{ $customerEmail }}">
                                                        Email customer
                                                    </x-dropdown-link>
                                                @else
                                                    <span class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-400">
                                                        Email customer
                                                    </span>
                                                @endif

                                                @if ($vendorEmail)
                                                    <x-dropdown-link href="mailto:{{ $vendorEmail }}">
                                                        Email vendor
                                                    </x-dropdown-link>
                                                @else
                                                    <span class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-400">
                                                        Email vendor
                                                    </span>
                                                @endif

                                                @if ($canReleaseSettlement)
                                                    <form method="POST" action="{{ route('admin.bookings.settle', $booking) }}">
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:bg-emerald-50 transition duration-150 ease-in-out"
                                                        >
                                                            Release settlement
                                                        </button>
                                                    </form>
                                                @endif
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-sm" style="color: #4A5565;">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
