<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use Illuminate\Support\Facades\Auth;

class VendorBookingController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $bookings = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        return view('vendor.bookings.index', compact('bookings'));
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
