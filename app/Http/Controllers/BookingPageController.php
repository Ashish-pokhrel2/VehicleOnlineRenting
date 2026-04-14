<?php

namespace App\Http\Controllers;

use App\Models\BookingSetting;
use App\Models\Bookings;
use App\Models\PickupTimeSlot;
use App\Models\Vehicles;
use Illuminate\View\View;

class BookingPageController extends Controller
{
    public function index(): View
    {
        $bookings = collect();
        $errorMessage = null;

        if (! auth()->check()) {
            $errorMessage = 'Sign in to view your bookings.';
        } else {
            $bookings = Bookings::with(['vehicle', 'vendor:id,name'])
                ->where('customer_id', auth()->id())
                ->latest()
                ->get();
        }

        $statusClasses = [
            'Confirmed' => 'status-confirmed',
            'Pending' => 'status-pending',
            'Completed' => 'status-completed',
            'Cancelled' => 'status-cancelled',
        ];

        return view('bookings.index', [
            'bookings' => $bookings,
            'statusClasses' => $statusClasses,
            'isLoading' => false,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function create(Vehicles $vehicle): View
    {
        $vehicle->load('vendor:id,name');

        $settings = BookingSetting::query()->latest()->first();
        $pickupTimeSlots = PickupTimeSlot::query()
            ->orderBy('sort_order')
            ->get();

        $estimatedDays = $settings?->default_estimated_days ?? 1;
        $serviceFee = $settings?->service_fee ?? 0;
        $subtotal = $vehicle->price_per_day * $estimatedDays;
        $total = $subtotal + $serviceFee;

        $availabilityLabel = $vehicle->available ? 'Available Now' : 'Currently Unavailable';

        return view('bookings.create', [
            'vehicle' => $vehicle,
            'pickupTimeSlots' => $pickupTimeSlots,
            'estimatedDays' => $estimatedDays,
            'serviceFee' => $serviceFee,
            'subtotal' => $subtotal,
            'total' => $total,
            'availabilityLabel' => $availabilityLabel,
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }
}
