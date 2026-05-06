<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\Bookings\StoreBookingsRequest;
use App\Models\Bookings;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        $query = Bookings::with(['vehicle', 'customer:id,name', 'vendor:id,name'])
            ->when($user->isCustomer(), fn ($q) => $q->where('customer_id', $user->id))
            ->when($user->isVendor(), fn ($q) => $q->where('vendor_id', $user->id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest();

        $bookings = $request->paginate
            ? $query->paginate($request->per_page ?? 15)
            : $query->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    public function store(StoreBookingsRequest $request): JsonResponse
    {
        $vehicle = Vehicles::findOrFail($request->vehicle_id);

        if (! $vehicle->available) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle is not available for booking',
            ], 422);
        }

        $days = now()->parse($request->start_date)
            ->diffInDays(now()->parse($request->end_date)) + 1;

        $totalPrice = $vehicle->price_per_day * $days;

        $booking = Bookings::create([
            'vehicle_id' => $request->vehicle_id,
            'customer_id' => auth()->id(),
            'vendor_id' => $vehicle->vendor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => BookingStatus::PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking->load(['vehicle', 'customer:id,name', 'vendor:id,name']),
        ], 201);
    }

    public function show(Bookings $booking): JsonResponse
    {
        $user = auth()->user();

        if (! $user || ($booking->customer_id !== $user->id && $booking->vendor_id !== $user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this booking',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $booking->load(['vehicle', 'customer:id,name', 'vendor:id,name']),
        ]);
    }

    public function updateStatus(Request $request, Bookings $booking): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:Confirmed,Cancelled,Completed'],
        ]);

        $user = auth()->user();

        if (! $user || ($booking->vendor_id !== $user->id && $booking->customer_id !== $user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this booking',
            ], 403);
        }

        $booking->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'data' => $booking->load(['vehicle', 'customer:id,name', 'vendor:id,name']),
        ]);
    }

    public function destroy(Bookings $booking): JsonResponse
    {
        if ($booking->customer_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this booking',
            ], 403);
        }

        if ($booking->status === BookingStatus::CONFIRMED) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete confirmed booking. Please cancel it first.',
            ], 422);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully',
        ]);
    }
}
