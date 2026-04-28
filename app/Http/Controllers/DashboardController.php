<?php

namespace App\Http\Controllers;

use App\Enums\BookingSettlementStatus;
use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Bookings;
use App\Models\BookingSettlement;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), Response::HTTP_FORBIDDEN);

        $heldSettlements = BookingSettlement::query()
            ->with([
                'booking.vehicle:id,name',
                'booking.customer:id,name',
                'vendor:id,name',
                'payment:id,booking_id,transaction_id,purchase_order_id',
            ])
            ->where('status', BookingSettlementStatus::HELD)
            ->latest('settled_at')
            ->latest('id')
            ->get();

        $totalUsers = User::query()->count();
        $activeUsers = User::query()->where('status', 'active')->count();
        $totalVehicles = Vehicles::query()->count();
        $availableVehicles = Vehicles::query()->where('available', true)->count();
        $totalBookings = Bookings::query()->count();
        $pendingBookings = Bookings::query()
            ->where('status', BookingStatus::PENDING)
            ->count();
        $totalRevenue = (float) Bookings::query()->sum('total_price');

        $roleCounts = [
            'Customers' => User::query()->where('role', UserRole::CUSTOMER)->count(),
            'Vendors' => User::query()->where('role', UserRole::VENDOR)->count(),
            'Admins' => User::query()->where('role', UserRole::ADMIN)->count(),
        ];

        $totalRoleUsers = max(array_sum($roleCounts), 1);

        $userDistribution = collect($roleCounts)->map(
            fn (int $count, string $label): array => [
                'label' => $label,
                'count' => $count,
                'percentage' => (int) round(($count / $totalRoleUsers) * 100),
            ]
        )->values();

        $latestBookings = Bookings::query()
            ->with([
                'vehicle:id,name,image',
                'customer:id,name',
            ])
            ->orderBy('id')
            ->limit(3)
            ->get();

        $recentActivities = [
            ['title' => 'New booking for Mercedes S-Class', 'time' => '2 hours ago'],
            ['title' => 'New vendor registered', 'time' => '5 hours ago'],
            ['title' => 'BMW Convertible added', 'time' => '1 day ago'],
            ['title' => 'Booking completed for Range Rover', 'time' => '2 days ago'],
        ];

        return view('admin.dashboard', [
            'heldSettlements' => $heldSettlements,
            'isAdmin' => true,
            'stats' => [
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'totalVehicles' => $totalVehicles,
                'availableVehicles' => $availableVehicles,
                'totalBookings' => $totalBookings,
                'pendingBookings' => $pendingBookings,
                'totalRevenue' => $totalRevenue,
            ],
            'userDistribution' => $userDistribution,
            'latestBookings' => $latestBookings,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function users(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), Response::HTTP_FORBIDDEN);

        $search = trim((string) $request->string('search')->value());

        $users = User::query()
            ->select(['id', 'name', 'email', 'phone', 'role', 'status', 'joined_date', 'created_at'])
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
            )
            ->orderBy('id')
            ->get();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'totalUsers' => User::query()->count(),
        ]);
    }

    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        $admin = $request->user();
        abort_unless($admin?->isAdmin(), Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($admin->is($user)) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $user->forceFill([
            'status' => $validated['status'],
        ])->save();

        $isActivating = $validated['status'] === 'active';
        $message = $isActivating
            ? 'User has been activated successfully.'
            : 'User has been deactivated successfully.';

        return back()->with([
            'statusMessage' => $message,
            'statusVariant' => $isActivating ? 'success' : 'danger',
        ]);
    }
}
