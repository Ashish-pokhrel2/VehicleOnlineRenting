<?php

namespace App\Http\Controllers;

use App\Enums\BookingSettlementStatus;
use App\Models\BookingSettlement;
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

        return view('admin.dashboard', [
            'heldSettlements' => $heldSettlements,
            'isAdmin' => true,
        ]);
    }
}
