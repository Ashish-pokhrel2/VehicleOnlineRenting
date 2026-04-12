<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Vehicles
    Route::apiResource('vehicles', VehiclesController::class);

    // Bookings
    Route::apiResource('bookings', BookingsController::class)->except(['update']);
    Route::patch('bookings/{booking}/status', [BookingsController::class, 'updateStatus']);

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
