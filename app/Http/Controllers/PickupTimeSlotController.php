<?php

namespace App\Http\Controllers;

use App\Models\PickupTimeSlot;
use Illuminate\Http\JsonResponse;

class PickupTimeSlotController extends Controller
{
    public function index(): JsonResponse
    {
        $slots = PickupTimeSlot::orderedWithDefaults();

        return response()->json([
            'success' => true,
            'data' => $slots,
        ]);
    }
}
