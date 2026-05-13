<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function earnings(Request $request)
    {
        $vendorId = Auth::id();

        $period = $request->query('period', 'month');

        if (! in_array($period, ['week', 'month'], true)) {
            $period = 'month';
        }

        $today = Carbon::today();

        $totalEarnings = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->sum('total_price');

        $thisMonthEarnings = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])
            ->sum('total_price');

        $pendingPayments = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::PENDING)
            ->sum('total_price');

        $confirmedBookings = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->count();

        $pendingBookings = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::PENDING)
            ->count();

        $cancelledBookings = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CANCELLED)
            ->count();

        $activeRentals = Bookings::where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $transactions = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->take(10)
            ->get();

        $topEarningVehicle = Bookings::with('vehicle')
            ->select('vehicle_id', DB::raw('SUM(total_price) as total_earned'), DB::raw('COUNT(*) as total_bookings'))
            ->where('vendor_id', $vendorId)
            ->where('status', BookingStatus::CONFIRMED)
            ->groupBy('vehicle_id')
            ->orderByDesc('total_earned')
            ->first();

        $chartLabels = [];
        $chartValues = [];

        if ($period === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);

                $chartLabels[] = $date->format('D');

                $chartValues[] = (float) Bookings::where('vendor_id', $vendorId)
                    ->where('status', BookingStatus::CONFIRMED)
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);

                $chartLabels[] = $month->format('M');

                $chartValues[] = (float) Bookings::where('vendor_id', $vendorId)
                    ->where('status', BookingStatus::CONFIRMED)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_price');
            }
        }

        $maxChartValue = max($chartValues) > 0 ? max($chartValues) : 1;

        return view('vendor.earnings', compact(
            'period',
            'totalEarnings',
            'thisMonthEarnings',
            'pendingPayments',
            'confirmedBookings',
            'pendingBookings',
            'cancelledBookings',
            'activeRentals',
            'transactions',
            'topEarningVehicle',
            'chartLabels',
            'chartValues',
            'maxChartValue'
        ));
    }
}