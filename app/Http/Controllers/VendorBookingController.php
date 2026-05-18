<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorBookingController extends Controller
{
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        $status = $request->query('status');

        $validStatuses = [
            BookingStatus::PENDING->value,
            BookingStatus::CONFIRMED->value,
            BookingStatus::COMPLETED->value,
            BookingStatus::CANCELLED->value,
        ];

        $bookings = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->when(
                in_array($status, $validStatuses, true),
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->get()
            ->unique(function ($booking) {
                return $booking->vehicle_id . '-' . ($booking->status->value ?? $booking->status);
            })
            ->values();

        return view('vendor.bookings.index', compact('bookings', 'status'));
    }

    public function confirm(Bookings $booking)
    {
        abort_unless($booking->vendor_id === Auth::id(), 403);

        if ($booking->status === BookingStatus::PENDING) {
            $booking->update([
                'status' => BookingStatus::CONFIRMED,
            ]);
        }

        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function reject(Bookings $booking)
    {
        abort_unless($booking->vendor_id === Auth::id(), 403);

        if ($booking->status === BookingStatus::PENDING) {
            $booking->update([
                'status' => BookingStatus::CANCELLED,
            ]);
        }

        return back()->with('success', 'Booking rejected successfully.');
    }
}