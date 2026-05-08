<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\BookingSetting;
use App\Models\PickupTimeSlot;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BookingPageController extends Controller
{
    // Display all bookings for the authenticated user
    public function index(): View
    {
        $this->ensureCustomer();

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
    public function create(Request $request, Vehicles $vehicle): View
    {
        $this->ensureCustomer();

        return $this->renderBookingForm($request, $vehicle);
    }

    // Display the booking modification page for an existing booking
    public function edit(Request $request, Bookings $booking): View
    {
        $this->ensureCustomer();

        abort_unless(
            $booking->customer_id === auth()->id() && $booking->status === BookingStatus::PENDING,
            Response::HTTP_FORBIDDEN
        );

        $booking->loadMissing('vehicle.vendor:id,name');

        return $this->renderBookingForm($request, $booking->vehicle, $booking);
    }

    // Handle booking form submission and save booking data
    public function store(Request $request): RedirectResponse
    {
        $this->ensureCustomer();

        // Validate required booking form inputs
        $validated = $this->validateBookingInput($request);

        // Find the selected vehicle from the database
        $vehicle = Vehicles::findOrFail($validated['vehicle_id']);
        $pricing = $this->calculateBookingPricing($validated['start_date'], $validated['end_date'], $vehicle->price_per_day);

        // Create a new booking record
        Bookings::create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => auth()->id(),
            'vendor_id' => $vehicle->vendor_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'pickup_time' => $validated['pickup_time'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'citizenship_id' => $validated['citizenship_id'],
            'special_request' => $validated['special_request'],
            'total_price' => $pricing['total_price'],
            'status' => BookingStatus::PENDING,
        ]);

        // Redirect user to booking history with success message
        return redirect()
            ->route('user.bookings')
            ->with('success', 'Booking confirmed successfully.');
    }

    // Handle pending booking modification and save updated booking data
    public function update(Request $request, Bookings $booking): RedirectResponse
    {
        $this->ensureCustomer();

        abort_unless(
            $booking->customer_id === auth()->id() && $booking->status === BookingStatus::PENDING,
            Response::HTTP_FORBIDDEN
        );

        $validated = $this->validateBookingInput($request);
        $pricing = $this->calculateBookingPricing(
            $validated['start_date'],
            $validated['end_date'],
            $booking->vehicle->price_per_day
        );

        $booking->update([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'pickup_time' => $validated['pickup_time'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'citizenship_id' => $validated['citizenship_id'],
            'special_request' => $validated['special_request'],
            'total_price' => $pricing['total_price'],
        ]);

        return redirect()
            ->route('user.bookings')
            ->with('success', 'Booking updated successfully.');
    }

    // Handle cancellation of a pending booking via AJAX
    public function cancel(Request $request, Bookings $booking): JsonResponse
    {
        $this->ensureCustomer();

        abort_unless(
            $booking->customer_id === auth()->id() && $booking->status === BookingStatus::PENDING,
            Response::HTTP_FORBIDDEN
        );

        $booking->update(['status' => BookingStatus::CANCELLED]);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully.',
        ]);
    }

    private function renderBookingForm(Request $request, Vehicles $vehicle, ?Bookings $booking = null): View
    {
        // Load vendor information for the selected vehicle
        $vehicle->load('vendor:id,name');

        // Fetch booking settings and pickup time slot options
        $settings = BookingSetting::query()->latest()->first();
        $pickupTimeSlots = PickupTimeSlot::orderedWithDefaults();

        // Calculate estimated booking values
        $estimatedDays = $this->resolveEstimatedDays($request, $settings?->default_estimated_days ?? 1, $booking);
        $serviceFee = $settings?->service_fee ?? 0;
        $subtotal = $vehicle->price_per_day * $estimatedDays;
        $total = $subtotal + $serviceFee;

        // Set vehicle availability label for display
        $availabilityLabel = $vehicle->available ? 'Available Now' : 'Currently Unavailable';

        // Return booking page data to the view
        return view('bookings.create', [
            'vehicle' => $vehicle,
            'editingBooking' => $booking,
            'pickupTimeSlots' => $pickupTimeSlots,
            'estimatedDays' => $estimatedDays,
            'serviceFee' => $serviceFee,
            'subtotal' => $subtotal,
            'total' => $total,
            'formDefaults' => [
                'start_date' => $booking?->start_date?->format('Y-m-d'),
                'end_date' => $booking?->end_date?->format('Y-m-d'),
                'pickup_time' => $booking?->pickup_time,
                'full_name' => $booking?->full_name,
                'phone' => $booking?->phone,
                'email' => $booking?->email,
                'citizenship_id' => $booking?->citizenship_id,
                'special_request' => $booking?->special_request,
            ],
            'availabilityLabel' => $availabilityLabel,
            'isLoading' => false,
            'errorMessage' => null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBookingInput(Request $request): array
    {
        return $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'citizenship_id' => 'required|string|max:255',
            'special_request' => 'required|string|max:2000',
        ]);
    }

    /**
     * @return array{days:int,total_price:float}
     */
    private function calculateBookingPricing(string $startDate, string $endDate, float $pricePerDay): array
    {
        $days = max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));

        return [
            'days' => $days,
            'total_price' => $pricePerDay * $days,
        ];
    }

    private function resolveEstimatedDays(Request $request, int $defaultDays, ?Bookings $booking = null): int
    {
        $startDate = $request->old('start_date');
        $endDate = $request->old('end_date');

        if (! is_string($startDate) || ! is_string($endDate) || $startDate === '' || $endDate === '') {
            return $this->bookingDaysOrDefault($booking, $defaultDays);
        }

        if (strtotime($startDate) === false || strtotime($endDate) === false) {
            return $this->bookingDaysOrDefault($booking, $defaultDays);
        }

        return max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate), false));
    }

    private function bookingDaysOrDefault(?Bookings $booking, int $defaultDays): int
    {
        if ($booking?->start_date && $booking?->end_date) {
            return max(1, $booking->start_date->diffInDays($booking->end_date, false));
        }

        return $defaultDays;
    }

    private function ensureCustomer(): void
    {
        abort_unless(auth()->user()?->isCustomer(), Response::HTTP_FORBIDDEN);
    }
}
