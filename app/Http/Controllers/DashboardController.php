<?php

namespace App\Http\Controllers;

use App\Enums\BookingSettlementStatus;
use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Bookings;
use App\Models\BookingSettlement;
use App\Models\Contact;
use App\Models\ContactMessage;
use App\Models\Reviews;
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

    public function bookings(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), Response::HTTP_FORBIDDEN);

        $search = trim((string) $request->string('search')->value());

        $bookings = Bookings::query()
            ->with([
                'vehicle:id,name,image',
                'customer:id,name',
                'vendor:id,name',
            ])
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('id', 'like', "%{$search}%")
                        ->orWhereHas('vehicle', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('vendor', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                })
            )
            ->orderBy('id')
            ->get();

        return view('admin.bookings', [
            'bookings' => $bookings,
            'search' => $search,
            'totalBookings' => Bookings::query()->count(),
        ]);
    }

    public function contact(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), Response::HTTP_FORBIDDEN);

        $customerMessages = Contact::query()
            ->select(['id', 'user_id', 'subject', 'message', 'status', 'created_at'])
            ->with('user:id,name,email')
            ->latest('id')
            ->get()
            ->map(fn (Contact $contact): array => [
                'key' => 'customer-'.$contact->id,
                'sender_name' => $contact->user?->name ?? 'Customer',
                'sender_email' => $contact->user?->email ?? 'N/A',
                'subject' => $contact->subject,
                'message' => $contact->message,
                'status' => $contact->status?->value ?? 'Pending',
                'source' => 'Customer',
                'created_at' => $contact->created_at,
            ])
            ->toBase();

        $vendorMessages = ContactMessage::query()
            ->select(['id', 'vendor_id', 'name', 'email', 'subject', 'message', 'status', 'created_at'])
            ->with('vendor:id,name')
            ->latest('id')
            ->get()
            ->map(fn (ContactMessage $message): array => [
                'key' => 'vendor-'.$message->id,
                'sender_name' => $message->name,
                'sender_email' => $message->email,
                'subject' => $message->subject,
                'message' => $message->message,
                'status' => $message->status,
                'source' => $message->vendor?->name
                    ? 'Vendor ('.$message->vendor->name.')'
                    : 'Vendor',
                'created_at' => $message->created_at,
            ])
            ->toBase();

        $messages = $customerMessages
            ->merge($vendorMessages)
            ->sortByDesc('created_at')
            ->values();

        return view('admin.contact', [
            'messages' => $messages,
            'totalMessages' => $messages->count(),
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

    public function vendors(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->isAdmin(), Response::HTTP_FORBIDDEN);

        $search = trim((string) $request->string('search')->value());

        // Get all vendors with their vehicles and bookings
        $vendors = User::query()
            ->where('role', UserRole::VENDOR)
            ->with([
                'vehicles:id,vendor_id,name,available',
                'vendorBookings:id,vendor_id,status,total_price',
                'vendorReviews:id,vendor_id,rating',
            ])
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
            )
            ->orderBy('name')
            ->get()
            ->map(function (User $vendor) {
                $bookings = $vendor->vendorBookings;
                $totalBookings = $bookings->count();
                $confirmedBookings = $bookings->where('status', BookingStatus::CONFIRMED)->count();
                $completedBookings = $bookings->where('status', BookingStatus::COMPLETED)->count();
                $cancelledBookings = $bookings->where('status', BookingStatus::CANCELLED)->count();
                $totalRevenue = (float) $bookings->sum('total_price');

                $acceptRate = $totalBookings > 0
                    ? round(($confirmedBookings / $totalBookings) * 100, 2)
                    : 0;

                $cancelRate = $totalBookings > 0
                    ? round(($cancelledBookings / $totalBookings) * 100, 2)
                    : 0;

                $completionRate = $totalBookings > 0
                    ? round(($completedBookings / $totalBookings) * 100, 2)
                    : 0;

                $avgRating = $vendor->vendorReviews->count() > 0
                    ? round($vendor->vendorReviews->avg('rating'), 2)
                    : 0;

                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'phone' => $vendor->phone,
                    'status' => $vendor->status,
                    'joinedDate' => $vendor->joined_date?->format('M d, Y') ?? 'N/A',
                    'vehicleCount' => $vendor->vehicles->count(),
                    'activeVehicles' => $vendor->vehicles->where('available', true)->count(),
                    'totalBookings' => $totalBookings,
                    'confirmedBookings' => $confirmedBookings,
                    'completedBookings' => $completedBookings,
                    'cancelledBookings' => $cancelledBookings,
                    'totalRevenue' => $totalRevenue,
                    'acceptRate' => $acceptRate,
                    'cancelRate' => $cancelRate,
                    'completionRate' => $completionRate,
                    'reviewCount' => $vendor->vendorReviews->count(),
                    'avgRating' => $avgRating,
                ];
            });

        // Calculate overall statistics
        $totalVendors = User::where('role', UserRole::VENDOR)->count();
        $activeVendors = User::where('role', UserRole::VENDOR)
            ->where('status', 'active')
            ->count();
        $totalVendorRevenue = (float) Bookings::query()->sum('total_price');

        return view('admin.vendors', [
            'vendors' => $vendors,
            'search' => $search,
            'stats' => [
                'totalVendors' => $totalVendors,
                'activeVendors' => $activeVendors,
                'totalVendorRevenue' => $totalVendorRevenue,
            ],
        ]);
    }

    public function showVendor(Request $request, User $vendor): View
    {
        $admin = $request->user();
        abort_unless($admin?->isAdmin(), Response::HTTP_FORBIDDEN);
        abort_unless($vendor->isVendor(), Response::HTTP_NOT_FOUND);

        // Get vendor with all relationships
        $vendor->load([
            'vehicles:id,vendor_id,name,available,price_per_day,rating,reviews',
            'vendorBookings:id,vendor_id,customer_id,vehicle_id,start_date,end_date,status,total_price,created_at',
            'vendorReviews:id,vendor_id,customer_id,vehicle_id,rating,comment,created_at',
        ]);

        // Calculate booking statistics
        $bookings = $vendor->vendorBookings;
        $totalBookings = $bookings->count();
        $confirmedBookings = $bookings->where('status', BookingStatus::CONFIRMED)->count();
        $completedBookings = $bookings->where('status', BookingStatus::COMPLETED)->count();
        $cancelledBookings = $bookings->where('status', BookingStatus::CANCELLED)->count();
        $pendingBookings = $bookings->where('status', BookingStatus::PENDING)->count();

        $totalRevenue = (float) $bookings->sum('total_price');
        $avgRevenuePerBooking = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        $acceptRate = $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 2) : 0;
        $cancelRate = $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
        $completionRate = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 2) : 0;

        // Get detailed bookings data
        $recentBookings = $bookings->sortByDesc('created_at')->take(10)->values();

        // Calculate review statistics
        $reviews = $vendor->vendorReviews;
        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 2) : 0;
        $totalReviews = $reviews->count();
        $ratingDistribution = [
            '5' => $reviews->where('rating', 5)->count(),
            '4' => $reviews->where('rating', 4)->count(),
            '3' => $reviews->where('rating', 3)->count(),
            '2' => $reviews->where('rating', 2)->count(),
            '1' => $reviews->where('rating', 1)->count(),
        ];

        // Get recent reviews
        $recentReviews = $reviews->sortByDesc('created_at')->take(5)->values();

        // Vehicle performance metrics
        $vehicleMetrics = $vendor->vehicles->map(function (Vehicles $vehicle) {
            $vehicleBookings = Bookings::where('vehicle_id', $vehicle->id)->get();
            $vehicleRevenue = (float) $vehicleBookings->sum('total_price');

            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'available' => $vehicle->available,
                'pricePerDay' => $vehicle->price_per_day,
                'rating' => $vehicle->rating,
                'reviews' => $vehicle->reviews,
                'bookingCount' => $vehicleBookings->count(),
                'revenue' => $vehicleRevenue,
            ];
        })->sortByDesc('revenue')->values();

        return view('admin.vendor-details', [
            'vendor' => $vendor,
            'stats' => [
                'totalBookings' => $totalBookings,
                'confirmedBookings' => $confirmedBookings,
                'completedBookings' => $completedBookings,
                'cancelledBookings' => $cancelledBookings,
                'pendingBookings' => $pendingBookings,
                'totalRevenue' => $totalRevenue,
                'avgRevenuePerBooking' => $avgRevenuePerBooking,
                'acceptRate' => $acceptRate,
                'cancelRate' => $cancelRate,
                'completionRate' => $completionRate,
                'totalVehicles' => $vendor->vehicles->count(),
                'activeVehicles' => $vendor->vehicles->where('available', true)->count(),
            ],
            'reviewStats' => [
                'totalReviews' => $totalReviews,
                'avgRating' => $avgRating,
                'ratingDistribution' => $ratingDistribution,
            ],
            'recentBookings' => $recentBookings,
            'recentReviews' => $recentReviews,
            'vehicleMetrics' => $vehicleMetrics,
        ]);
    }
}
