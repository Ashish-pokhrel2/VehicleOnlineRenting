<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $totalVehicles = Vehicles::where('vendor_id', $vendorId)->count();

        $allBookings = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        $uniqueBookings = $this->uniqueBookings($allBookings);

        $totalBookings = $uniqueBookings->count();

        $pendingRequests = $uniqueBookings
            ->where('status', BookingStatus::PENDING)
            ->count();

        $totalRevenue = $uniqueBookings
            ->where('status', BookingStatus::CONFIRMED)
            ->sum('total_price');

        $recentBookings = $uniqueBookings
            ->take(5);

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

        $allBookings = Bookings::with(['vehicle', 'customer'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->get();

        $uniqueBookings = $this->uniqueBookings($allBookings);

        $confirmedUniqueBookings = $uniqueBookings
            ->where('status', BookingStatus::CONFIRMED);

        $pendingUniqueBookings = $uniqueBookings
            ->where('status', BookingStatus::PENDING);

        $cancelledUniqueBookings = $uniqueBookings
            ->where('status', BookingStatus::CANCELLED);

        $totalEarnings = $confirmedUniqueBookings
            ->sum('total_price');

        $thisMonthEarnings = $confirmedUniqueBookings
            ->filter(function ($booking) {
                return $booking->created_at
                    && $booking->created_at->between(
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    );
            })
            ->sum('total_price');

        $pendingPayments = $pendingUniqueBookings
            ->sum('total_price');

        $confirmedBookings = $confirmedUniqueBookings
            ->count();

        $pendingBookings = $pendingUniqueBookings
            ->count();

        $cancelledBookings = $cancelledUniqueBookings
            ->count();

        $activeRentals = $confirmedUniqueBookings
            ->filter(function ($booking) use ($today) {
                return $booking->start_date
                    && $booking->end_date
                    && Carbon::parse($booking->start_date)->lte($today)
                    && Carbon::parse($booking->end_date)->gte($today);
            })
            ->count();

        $transactions = $uniqueBookings
            ->take(10);

        $topEarningVehicleGroup = $confirmedUniqueBookings
            ->groupBy('vehicle_id')
            ->map(function ($group) {
                $firstBooking = $group->first();

                return (object) [
                    'vehicle_id' => $firstBooking?->vehicle_id,
                    'vehicle' => $firstBooking?->vehicle,
                    'total_earned' => $group->sum('total_price'),
                    'total_bookings' => $group->count(),
                ];
            })
            ->sortByDesc('total_earned')
            ->first();

        $topEarningVehicle = $topEarningVehicleGroup;

        $chartLabels = [];
        $chartValues = [];

        if ($period === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);

                $chartLabels[] = $date->format('D');

                $chartValues[] = (float) $confirmedUniqueBookings
                    ->filter(function ($booking) use ($date) {
                        return $booking->created_at
                            && $booking->created_at->isSameDay($date);
                    })
                    ->sum('total_price');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);

                $chartLabels[] = $month->format('M');

                $chartValues[] = (float) $confirmedUniqueBookings
                    ->filter(function ($booking) use ($month) {
                        return $booking->created_at
                            && $booking->created_at->year === $month->year
                            && $booking->created_at->month === $month->month;
                    })
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

    private function uniqueBookings($bookings)
    {
        return $bookings
            ->unique(function ($booking) {
                return $booking->vehicle_id . '-' . ($booking->status->value ?? $booking->status);
            })
            ->values();
    }
}