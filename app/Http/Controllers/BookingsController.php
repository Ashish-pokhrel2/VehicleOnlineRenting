<?php

namespace App\Http\Controllers;

use App\Enums\BookingPaymentStatus;
use App\Enums\BookingStatus;
use App\Http\Requests\Bookings\StoreBookingsRequest;
use App\Models\BookingPayment;
use App\Models\Bookings;
use App\Models\BookingSetting;
use App\Models\Vehicles;
use App\Services\EsewaEpayPaymentService;
use App\Services\KhaltiPaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class BookingsController extends Controller
{
    public function __construct(
        private readonly KhaltiPaymentService $khaltiPaymentService,
        private readonly EsewaEpayPaymentService $esewaPaymentService
    ) {}

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

    public function store(StoreBookingsRequest $request): JsonResponse|RedirectResponse|View
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

        $settings = BookingSetting::query()->latest()->first();
        $serviceFee = (float) ($settings?->service_fee ?? 0);
        $totalPrice = ($vehicle->price_per_day * $days) + $serviceFee;

        $booking = Bookings::create([
            'vehicle_id' => $request->vehicle_id,
            'customer_id' => auth()->id(),
            'vendor_id' => $vehicle->vendor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => BookingStatus::PENDING,
        ]);

        $paymentMethod = $request->input('payment_method', 'khalti');

        try {
            if ($paymentMethod === 'esewa') {
                $payment = $this->createEsewaPayment($booking, $serviceFee);
                $payload = $this->esewaPaymentService->paymentPayload(
                    $payment,
                    route('esewa.payments.success', $payment),
                    route('esewa.payments.failure', $payment)
                );

                $payment->update([
                    'pidx' => $payment->purchase_order_id,
                    'payment_url' => $this->esewaPaymentService->epayUrl(),
                    'response_payload' => $payload,
                    'return_url' => route('esewa.payments.success', $payment),
                    'status' => BookingPaymentStatus::INITIATED,
                    'initiated_at' => now(),
                ]);
            } else {
                $payment = $this->createKhaltiPayment($booking, $serviceFee);
                $response = $this->khaltiPaymentService->initiate(
                    $payment,
                    $request->user(),
                    route('khalti.payments.return')
                );

                $payment->update([
                    'pidx' => $response['pidx'],
                    'payment_url' => $response['payment_url'],
                    'response_payload' => $response,
                    'status' => BookingPaymentStatus::INITIATED,
                    'initiated_at' => now(),
                    'expired_at' => isset($response['expires_at']) ? Carbon::parse($response['expires_at']) : null,
                ]);
            }
        } catch (RuntimeException $exception) {
            $booking->update(['status' => BookingStatus::CANCELLED]);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        if (! $request->expectsJson() && $paymentMethod === 'esewa') {
            return view('payments.esewa-redirect', [
                'epayUrl' => $this->esewaPaymentService->epayUrl(),
                'payload' => $payload,
            ]);
        }

        if (! $request->expectsJson()) {
            return redirect()->away($payment->payment_url);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment']),
            'payment_url' => $payment->payment_url,
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

    private function createKhaltiPayment(Bookings $booking, float $serviceFee): BookingPayment
    {
        $amount = (float) $booking->total_price;

        return BookingPayment::create([
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'vendor_id' => $booking->vendor_id,
            'purchase_order_id' => 'booking-'.$booking->id.'-'.Str::upper(Str::random(8)),
            'purchase_order_name' => 'Vehicle booking #'.$booking->id,
            'amount' => $amount,
            'service_fee' => $serviceFee,
            'net_amount' => max(0, $amount - $serviceFee),
            'return_url' => route('khalti.payments.return'),
            'website_url' => config('app.url'),
            'status' => BookingPaymentStatus::INITIATED,
            'request_payload' => [
                'booking_id' => $booking->id,
                'payment_method' => 'khalti',
            ],
        ]);
    }

    private function createEsewaPayment(Bookings $booking, float $serviceFee): BookingPayment
    {
        $amount = (float) $booking->total_price;

        return BookingPayment::create([
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'vendor_id' => $booking->vendor_id,
            'purchase_order_id' => 'booking-'.$booking->id.'-'.Str::upper(Str::random(8)),
            'purchase_order_name' => 'Vehicle booking #'.$booking->id,
            'amount' => $amount,
            'service_fee' => $serviceFee,
            'net_amount' => max(0, $amount - $serviceFee),
            'website_url' => config('app.url'),
            'status' => BookingPaymentStatus::INITIATED,
            'request_payload' => [
                'booking_id' => $booking->id,
                'payment_method' => 'esewa',
            ],
        ]);
    }
}
