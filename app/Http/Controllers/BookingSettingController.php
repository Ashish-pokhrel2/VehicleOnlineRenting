<?php

namespace App\Http\Controllers;

use App\Models\BookingSetting;
use Illuminate\Http\JsonResponse;

class BookingSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = BookingSetting::query()->latest()->first();

        return response()->json([
            'success' => true,
            'data' => [
                'service_fee' => $settings?->service_fee ?? 0,
                'default_estimated_days' => $settings?->default_estimated_days ?? 1,
            ],
        ]);
    }
}
