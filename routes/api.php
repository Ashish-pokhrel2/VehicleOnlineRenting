<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\BookingSettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EsewaPaymentController;
use App\Http\Controllers\PickupTimeSlotController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum,web')->group(function () {
    // Vehicles
    Route::apiResource('vehicles', VehiclesController::class);

    // Bookings - Define custom route BEFORE apiResource so it has priority
    Route::patch('bookings/{booking}/status', [BookingsController::class, 'updateStatus']);
    Route::apiResource('bookings', BookingsController::class)->except(['update']);

    // Reviews
    Route::apiResource('reviews', ReviewsController::class)->only(['index', 'store', 'show', 'destroy']);

    // Contacts
    Route::apiResource('contacts', ContactController::class)->except(['update']);
    Route::patch('contacts/{contact}/read', [ContactController::class, 'markAsRead']);
    Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus']);
});

// Public routes
Route::get('vehicles', [VehiclesController::class, 'index']);
Route::get('vehicles/{vehicle}', [VehiclesController::class, 'show']);
Route::get('reviews', [ReviewsController::class, 'index']);
Route::get('pickup-time-slots', [PickupTimeSlotController::class, 'index']);
Route::get('booking-settings', [BookingSettingController::class, 'index']);

Route::post('payments/esewa/callback', [EsewaPaymentController::class, 'callback'])
    ->middleware('throttle:30,1')
    ->name('esewa.payments.callback');
