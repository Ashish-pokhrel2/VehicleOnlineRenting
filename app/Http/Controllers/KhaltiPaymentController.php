<?php

namespace App\Http\Controllers;

use App\Enums\BookingPaymentStatus;
use App\Enums\BookingSettlementStatus;
use App\Enums\BookingStatus;
use App\Models\BookingPayment;
use App\Models\BookingSettlement;
use App\Notifications\BookingCreatedNotification;
use App\Services\KhaltiPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class KhaltiPaymentController extends Controller
{
    public function __construct(private readonly KhaltiPaymentService $khaltiPaymentService) {}

    public function callback(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'pidx' => ['required', 'string'],
            'status' => ['required', 'string'],
            'purchase_order_id' => ['nullable', 'string'],
        ]);

        try {
            $result = $this->verifyPayment($validated);

            if (($result['success'] ?? true) === false) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'] ?? 'Payment verification failed.',
                        'data' => $result,
                    ], 422);
                }

                return redirect()
                    ->route('user.bookings')
                    ->with('errorMessage', $result['message'] ?? 'Payment verification failed.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully.',
                    'data' => $result,
                ]);
            }

            return redirect()
                ->route('user.bookings')
                ->with('success', 'Payment verified successfully. Your booking is now confirmed.');
        } catch (RuntimeException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('user.bookings')
                ->with('errorMessage', $exception->getMessage());
        }
    }

    private function verifyPayment(array $validated): array
    {
        return DB::transaction(function () use ($validated): array {
            $paymentQuery = BookingPayment::query()
                ->with(['booking', 'customer'])
                ->lockForUpdate()
                ->where('pidx', $validated['pidx']);

            if (! empty($validated['purchase_order_id'])) {
                $paymentQuery->where('purchase_order_id', $validated['purchase_order_id']);
            }

            $payment = $paymentQuery->first();

            $returnStatus = (string) ($validated['status'] ?? '');

            if (! $payment) {
                throw new RuntimeException('Payment record not found.');
            }

            if (in_array($payment->status, [BookingPaymentStatus::COMPLETED, BookingPaymentStatus::REFUNDED], true)) {
                return [
                    'success' => true,
                    'booking' => $payment->booking,
                    'payment' => $payment,
                    'settlement' => $payment->settlement,
                ];
            }

            if (in_array($returnStatus, ['User canceled', 'Expired'], true)) {
                $failedStatus = $returnStatus === 'Expired'
                    ? BookingPaymentStatus::EXPIRED
                    : BookingPaymentStatus::CANCELED;

                $payment->update([
                    'status' => $failedStatus,
                    'lookup_payload' => [
                        'status' => $returnStatus,
                        'source' => 'khalti_return',
                    ],
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'Khalti payment was not completed.',
                    'booking' => $payment->booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment', 'latestSettlement']),
                    'payment' => $payment->fresh(['booking', 'customer', 'vendor', 'settlement']),
                    'settlement' => $payment->settlement,
                    'lookup' => $payment->lookup_payload,
                ];
            }

            $lookup = $this->khaltiPaymentService->lookup($validated['pidx']);
            $expectedAmount = (int) round($payment->amount * 100);
            $lookupAmount = (int) ($lookup['total_amount'] ?? 0);
            $lookupStatus = (string) ($lookup['status'] ?? '');

            if (in_array($lookupStatus, ['User canceled', 'Expired', 'Refunded'], true)) {
                $failedStatus = match ($lookupStatus) {
                    'User canceled' => BookingPaymentStatus::CANCELED,
                    'Expired' => BookingPaymentStatus::EXPIRED,
                    default => BookingPaymentStatus::REFUNDED,
                };

                $payment->update([
                    'status' => $failedStatus,
                    'lookup_payload' => $lookup,
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'Khalti payment was not completed.',
                    'booking' => $payment->booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment', 'latestSettlement']),
                    'payment' => $payment->fresh(['booking', 'customer', 'vendor', 'settlement']),
                    'settlement' => $payment->settlement,
                    'lookup' => $lookup,
                ];
            }

            if (in_array($lookupStatus, ['Pending', 'Initiated'], true)) {
                $payment->update([
                    'status' => BookingPaymentStatus::PENDING,
                    'lookup_payload' => $lookup,
                    'verified_at' => now(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Khalti payment is pending. Please wait for final confirmation.',
                    'booking' => $payment->booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment', 'latestSettlement']),
                    'payment' => $payment->fresh(['booking', 'customer', 'vendor', 'settlement']),
                    'settlement' => $payment->settlement,
                    'lookup' => $lookup,
                ];
            }

            if ($lookupStatus !== 'Completed' || $lookupAmount !== $expectedAmount) {
                $payment->update([
                    'status' => BookingPaymentStatus::FAILED,
                    'lookup_payload' => $lookup,
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'Khalti verification failed or amount mismatch.',
                    'booking' => $payment->booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment', 'latestSettlement']),
                    'payment' => $payment->fresh(['booking', 'customer', 'vendor', 'settlement']),
                    'settlement' => $payment->settlement,
                    'lookup' => $lookup,
                ];
            }

            $payment->update([
                'status' => BookingPaymentStatus::COMPLETED,
                'transaction_id' => (string) ($lookup['transaction_id'] ?? $validated['pidx']),
                'lookup_payload' => $lookup,
                'verified_at' => now(),
            ]);

            $payment->booking->update(['status' => BookingStatus::CONFIRMED]);
            $payment->booking->loadMissing(['vehicle', 'customer', 'vendor']);

            if ($payment->booking->vendor) {
                $payment->booking->vendor->notify(new BookingCreatedNotification($payment->booking));
            }

            $settlement = BookingSettlement::firstOrCreate(
                ['booking_payment_id' => $payment->id],
                [
                    'booking_id' => $payment->booking_id,
                    'vendor_id' => $payment->vendor_id,
                    'gross_amount' => $payment->amount,
                    'service_fee' => $payment->service_fee,
                    'net_amount' => $payment->net_amount,
                    'status' => BookingSettlementStatus::HELD,
                    'settled_at' => now(),
                ]
            );

            return [
                'success' => true,
                'booking' => $payment->booking->load(['vehicle', 'customer:id,name', 'vendor:id,name', 'latestPayment', 'latestSettlement']),
                'payment' => $payment->fresh(['booking', 'customer', 'vendor', 'settlement']),
                'settlement' => $settlement,
                'lookup' => $lookup,
            ];
        }, 3);
    }
}
