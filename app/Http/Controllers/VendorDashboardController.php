<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $totalVehicles = Vehicles::where('vendor_id', $vendorId)->count();

        $totalBookings = Bookings::where('vendor_id', $vendorId)->count();

        $pendingRequests = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::PENDING)
            ->count();

        $totalRevenue = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->sum('total_price');

        $recentBookings = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        $topVehicles = Vehicles::where('vendor_id', $vendorId)
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        return view('vendor.dashboard', compact(
            'totalVehicles',
            'totalBookings',
            'pendingRequests',
            'totalRevenue',
            'recentBookings',
            'topVehicles'
        ));
    }
}
