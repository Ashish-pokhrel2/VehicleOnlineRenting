<?php

namespace App\Http\Controllers;

use App\Enums\BookingSettlementStatus;
use App\Enums\BookingStatus;
use App\Models\Bookings;
use App\Models\BookingSettlement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BookingSettlementController extends Controller
{
    public function store(Request $request, Bookings $booking): JsonResponse|RedirectResponse
    {
        $this->authorize('settle', $booking);

        try {
            $settlement = DB::transaction(function () use ($booking): BookingSettlement {
                $booking->loadMissing(['latestPayment', 'latestSettlement']);

                $payment = $booking->latestPayment;

                if (! $payment) {
                    throw new RuntimeException('A completed payment is required before settlement.');
                }

                if ($payment->status->value !== 'Completed') {
                    throw new RuntimeException('Only completed payments can be settled.');
                }

                $settlement = BookingSettlement::firstOrCreate(
                    ['booking_payment_id' => $payment->id],
                    [
                        'booking_id' => $booking->id,
                        'vendor_id' => $booking->vendor_id,
                        'gross_amount' => $payment->amount,
                        'service_fee' => $payment->service_fee,
                        'net_amount' => $payment->net_amount,
                        'status' => BookingSettlementStatus::HELD,
                        'settled_at' => now(),
                    ]
                );

                if ($settlement->status === BookingSettlementStatus::RELEASED) {
                    return $settlement;
                }

                $settlement->update([
                    'admin_id' => auth()->id(),
                    'status' => BookingSettlementStatus::RELEASED,
                    'released_at' => now(),
                ]);

                $booking->update(['status' => BookingStatus::COMPLETED]);

                return $settlement->fresh();
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Settlement released successfully.',
                    'data' => $settlement->load(['booking', 'payment', 'vendor', 'admin']),
                ]);
            }

            return back()->with('success', 'Settlement released successfully.');
        } catch (RuntimeException $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return back()->with('errorMessage', $exception->getMessage());
        }
    }
}
