<?php

namespace App\Http\Controllers;

use App\Enums\BookingPaymentStatus;
use App\Enums\BookingSettlementStatus;
use App\Enums\BookingStatus;
use App\Models\BookingPayment;
use App\Models\BookingSettlement;
use App\Notifications\BookingCreatedNotification;
use App\Services\EsewaEpayPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EsewaPaymentController extends Controller
{
    public function __construct(private readonly EsewaEpayPaymentService $esewaPaymentService) {}

    public function callback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => ['required', 'string'],
        ]);

        try {
            $payload = $this->esewaPaymentService->decodeSuccessPayload($validated['data']);
            $payment = BookingPayment::query()
                ->where('purchase_order_id', $payload['transaction_uuid'] ?? null)
                ->firstOrFail();

            $result = $this->verifyCompletedPayment($payment, $payload);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result,
            ]);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function success(Request $request, BookingPayment $payment): RedirectResponse
    {
        abort_unless($request->user()?->id === $payment->customer_id, 403);

        if (in_array($payment->status, [BookingPaymentStatus::COMPLETED, BookingPaymentStatus::REFUNDED], true)) {
            return redirect()
                ->route('user.bookings')
                ->with('success', 'Payment verified successfully. Your booking is now confirmed.');
        }

        $encodedPayload = $request->query('data');

        if (! is_string($encodedPayload) || $encodedPayload === '') {
            return redirect()
                ->route('user.bookings')
                ->with('errorMessage', 'Unable to verify eSewa payment. Please contact support.');
        }

        try {
            $payload = $this->esewaPaymentService->decodeSuccessPayload($encodedPayload);
            $result = $this->verifyCompletedPayment($payment, $payload);

            return redirect()
                ->route('user.bookings')
                ->with($result['success'] ? 'success' : 'errorMessage', $result['message']);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('user.bookings')
                ->with('errorMessage', $exception->getMessage());
        }
    }

    public function failure(Request $request, BookingPayment $payment): RedirectResponse
    {
        abort_unless($request->user()?->id === $payment->customer_id, 403);

        $payment->update([
            'status' => BookingPaymentStatus::FAILED,
            'verified_at' => now(),
        ]);

        $payment->booking->update(['status' => BookingStatus::CANCELLED]);

        return redirect()
            ->route('user.bookings')
            ->with('errorMessage', 'eSewa payment was not completed.');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function verifyCompletedPayment(BookingPayment $payment, array $payload): array
    {
        $requiredFields = [
            'transaction_code',
            'status',
            'total_amount',
            'transaction_uuid',
            'product_code',
            'signed_field_names',
            'signature',
        ];

        foreach ($requiredFields as $field) {
            if (! isset($payload[$field]) || $payload[$field] === '') {
                throw new RuntimeException('eSewa returned an incomplete payment response.');
            }
        }

        if ((string) $payload['product_code'] !== (string) config('services.esewa.product_code')) {
            throw new RuntimeException('Invalid eSewa product code.');
        }

        if ((string) $payload['transaction_uuid'] !== $payment->purchase_order_id) {
            throw new RuntimeException('Invalid eSewa transaction reference.');
        }

        if (! $this->esewaPaymentService->verifySignature(
            (string) $payload['signed_field_names'],
            $payload,
            (string) $payload['signature']
        )) {
            throw new RuntimeException('Invalid eSewa payment signature.');
        }

        $statusLookup = $this->esewaPaymentService->status($payment);

        return $this->handleEsewaStatusUpdate($payment, $payload, $statusLookup);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $statusLookup
     * @return array<string, mixed>
     */
    private function handleEsewaStatusUpdate(BookingPayment $payment, array $payload, array $statusLookup): array
    {
        return DB::transaction(function () use ($payment, $payload, $statusLookup): array {
            $payment = BookingPayment::query()
                ->with(['booking', 'customer'])
                ->lockForUpdate()
                ->findOrFail($payment->id);

            $expectedAmount = (float) $payment->amount;
            $receivedAmount = (float) $payload['total_amount'];

            if (round($expectedAmount, 2) !== round($receivedAmount, 2)) {
                $payment->update([
                    'status' => BookingPaymentStatus::FAILED,
                    'lookup_payload' => $statusLookup,
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'eSewa verification failed or amount mismatch.',
                    'payment' => $payment->fresh(),
                ];
            }

            if (in_array($payment->status, [BookingPaymentStatus::COMPLETED, BookingPaymentStatus::REFUNDED], true)) {
                return [
                    'success' => true,
                    'message' => 'Payment already verified.',
                    'payment' => $payment,
                ];
            }

            $status = strtoupper((string) ($statusLookup['status'] ?? $payload['status']));

            if ($status === 'PENDING') {
                $payment->update([
                    'status' => BookingPaymentStatus::PENDING,
                    'lookup_payload' => $statusLookup,
                    'verified_at' => now(),
                ]);

                return [
                    'success' => false,
                    'message' => 'eSewa payment is pending. Please wait for final confirmation.',
                    'payment' => $payment->fresh(),
                ];
            }

            if (in_array($status, ['NOT_FOUND', 'FAILED', 'CANCELED', 'AMBIGUOUS'], true)) {
                $payment->update([
                    'status' => $status === 'CANCELED' ? BookingPaymentStatus::CANCELED : BookingPaymentStatus::FAILED,
                    'lookup_payload' => $statusLookup,
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'eSewa payment was not completed.',
                    'payment' => $payment->fresh(),
                ];
            }

            if (! in_array($status, ['COMPLETE', 'SUCCESS'], true)) {
                $payment->update([
                    'status' => BookingPaymentStatus::FAILED,
                    'lookup_payload' => $statusLookup,
                    'verified_at' => now(),
                ]);

                $payment->booking->update(['status' => BookingStatus::CANCELLED]);

                return [
                    'success' => false,
                    'message' => 'eSewa verification failed.',
                    'payment' => $payment->fresh(),
                ];
            }

            $payment->update([
                'status' => BookingPaymentStatus::COMPLETED,
                'transaction_id' => (string) ($payload['transaction_code'] ?? $payment->transaction_id),
                'lookup_payload' => $statusLookup,
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
                'message' => 'Payment verified successfully. Your booking is now confirmed.',
                'payment' => $payment->fresh(),
                'settlement' => $settlement,
            ];
        }, 3);
    }
}
