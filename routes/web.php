<?php

use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiclePageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== Public Routes =====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Vehicles Listing Page
Route::get('/vehicles', [VehiclePageController::class, 'index'])->name('vehicles.index');

//Vehicle Detail Page
Route::get('/vehicles/{vehicle}', [VehiclePageController::class, 'show'])->name('vehicles.show');

// My Bookings Page
Route::get('/my-bookings', [BookingPageController::class, 'index'])->name('user.bookings');

// Booking Create Page 
Route::get('/bookings/create/{vehicle}', [BookingPageController::class, 'create'])->name('bookings.create');

// ===================== Authenticated Routes =====================

// Dashboard (Accessible only after login and email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ===================== Authentication Routes =====================
require __DIR__.'/auth.php';
