<?php

use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiclePageController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\VendorVehicleController;
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
    Route::get('/bookings/{booking}/edit', [BookingPageController::class, 'edit'])->name('bookings.edit');

    // Booking Store Route
    Route::post('/bookings', [BookingPageController::class, 'store'])->name('bookings.page.store');
    Route::patch('/bookings/{booking}', [BookingPageController::class, 'update'])->name('bookings.page.update');
});

// ===================== Authenticated Routes =====================

// Dashboard (Accessible only after login and email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        if ($request->user()?->isAdmin()) {
            return to_route('admin.dashboard');
        }

        if ($request->user()?->isVendor()) {
            return to_route('vendor.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // Vendor Routes
   Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    Route::get('/vehicles', [VendorVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [VendorVehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VendorVehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}/edit', [VendorVehicleController::class, 'edit'])->name('vehicles.edit');
    Route::patch('/vehicles/{vehicle}', [VendorVehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VendorVehicleController::class, 'destroy'])->name('vehicles.destroy');
});

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::patch('/users/{user}/status', [DashboardController::class, 'updateUserStatus'])->name('users.status');

        Route::get('/vehicles', function (Request $request) {
            abort_unless($request->user()?->isAdmin(), 403);

            return view('admin.vehicles');
        })->name('vehicles');

        Route::get('/contact', function (Request $request) {
            abort_unless($request->user()?->isAdmin(), 403);

            return view('admin.contact');
        })->name('contact');

        Route::get('/bookings', [DashboardController::class, 'bookings'])->name('bookings');
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