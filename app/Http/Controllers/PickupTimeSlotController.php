<?php

namespace App\Http\Controllers;

use App\Models\PickupTimeSlot;
use Illuminate\Http\JsonResponse;

class PickupTimeSlotController extends Controller
{
    public function index(): JsonResponse
    {
        $slots = PickupTimeSlot::query()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $slots,
        ]);
    }
}
