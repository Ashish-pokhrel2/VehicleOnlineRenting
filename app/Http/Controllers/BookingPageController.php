<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\BookingSetting;
use App\Models\Bookings;
use App\Models\PickupTimeSlot;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingPageController extends Controller
{
    // Display all bookings for the authenticated user
    public function index(): View
    {
        $bookings = collect();
        $errorMessage = null;

        // Show message if the user is not logged in
        if (! auth()->check()) {
            $errorMessage = 'Sign in to view your bookings.';
        } else {
            // Fetch bookings with related vehicle and vendor information
            $bookings = Bookings::with(['vehicle', 'vendor:id,name'])
                ->where('customer_id', auth()->id())
                ->latest()
                ->get();
        }

        // Define CSS classes for different booking statuses
        $statusClasses = [
            'Confirmed' => 'status-confirmed',
            'Pending' => 'status-pending',
            'Completed' => 'status-completed',
            'Cancelled' => 'status-cancelled',
        ];

        // Return booking history data to the view
        return view('bookings.index', [
            'bookings' => $bookings,
            'statusClasses' => $statusClasses,
            'isLoading' => false,
            'errorMessage' => $errorMessage,
        ]);
    }

    // Display the booking creation page for a selected vehicle
    public function create(Vehicles $vehicle): View
    {
        // Load vendor information for the selected vehicle
        $vehicle->load('vendor:id,name');

        // Fetch booking settings and pickup time slot options
        $settings = BookingSetting::query()->latest()->first();
        $pickupTimeSlots = PickupTimeSlot::query()
            ->orderBy('sort_order')
            ->get();

        // Calculate estimated booking values
        $estimatedDays = $settings?->default_estimated_days ?? 1;
        $serviceFee = $settings?->service_fee ?? 0;
        $subtotal = $vehicle->price_per_day * $estimatedDays;
        $total = $subtotal + $serviceFee;

        // Set vehicle availability label for display
        $availabilityLabel = $vehicle->available ? 'Available Now' : 'Currently Unavailable';

        // Return booking page data to the view
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

    // Handle booking form submission and save booking data
    public function store(Request $request): RedirectResponse
    {
        // Validate required booking form inputs
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Find the selected vehicle from the database
        $vehicle = Vehicles::findOrFail($validated['vehicle_id']);

        // Convert booking dates into Carbon instances for calculation
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // Calculate total booking days and booking price
        $days = max(1, $startDate->diffInDays($endDate));
        $totalPrice = $vehicle->price_per_day * $days;

        // Create a new booking record
        Bookings::create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => auth()->id(),
            'vendor_id' => $vehicle->vendor_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
            'status' => BookingStatus::PENDING,
        ]);

        // Redirect user to booking history with success message
        return redirect()
            ->route('user.bookings')
            ->with('success', 'Booking confirmed successfully.');
    }
}