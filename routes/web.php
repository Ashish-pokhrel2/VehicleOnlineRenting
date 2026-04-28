<?php

use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiclePageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== Public Routes =====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// AJAX Search Route
Route::get('/vehicles/search/ajax', [VehiclePageController::class, 'ajaxSearch'])->name('vehicles.search.ajax');

Route::middleware('auth')->group(function () {
    // Vehicles Listing Page
    Route::get('/vehicles', [VehiclePageController::class, 'index'])->name('vehicles.index');

    // Vehicle Detail Page
    Route::get('/vehicles/{vehicle}', [VehiclePageController::class, 'show'])->name('vehicles.show');

    // My Bookings Page
    Route::get('/my-bookings', [BookingPageController::class, 'index'])->name('user.bookings');

    // Booking Create Page
    Route::get('/bookings/create/{vehicle}', [BookingPageController::class, 'create'])->name('bookings.create');

    // Booking Store Route
    Route::post('/bookings', [BookingPageController::class, 'store'])->name('bookings.page.store');
});

// ===================== Authenticated Routes =====================

// Dashboard (Accessible only after login and email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        if ($request->user()?->isAdmin()) {
            return to_route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::patch('/users/{user}/status', [DashboardController::class, 'updateUserStatus'])->name('users.status');

        Route::get('/vehicles', function (Request $request) {
            abort_unless($request->user()?->isAdmin(), 403);

            return view('admin.vehicles');
        })->name('vehicles');

        Route::get('/bookings', function (Request $request) {
            abort_unless($request->user()?->isAdmin(), 403);

            return view('admin.bookings');
        })->name('bookings');
    });
});

// Profile Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================== Authentication Routes =====================
require __DIR__.'/auth.php';
