<?php

use App\Http\Controllers\BookingPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiclePageController;
use App\Http\Controllers\VendorBookingController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\VendorVehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ===================== Public Routes =====================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/vehicles/search/ajax', [VehiclePageController::class, 'ajaxSearch'])
    ->name('vehicles.search.ajax');

// ===================== Customer Routes =====================

Route::middleware('auth')->group(function () {
    Route::get('/vehicles', [VehiclePageController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{vehicle}', [VehiclePageController::class, 'show'])->name('vehicles.show');

    Route::get('/my-bookings', [BookingPageController::class, 'index'])->name('user.bookings');

    Route::get('/bookings/create/{vehicle}', [BookingPageController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}/edit', [BookingPageController::class, 'edit'])->name('bookings.edit');

    Route::post('/bookings', [BookingPageController::class, 'store'])->name('bookings.page.store');
    Route::patch('/bookings/{booking}', [BookingPageController::class, 'update'])->name('bookings.page.update');
});

// ===================== Authenticated Routes =====================

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

    // ===================== Vendor Routes =====================
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorDashboardController::class)->index();
        })->name('dashboard');

        Route::get('/vehicles', function (Request $request) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->index();
        })->name('vehicles.index');

        Route::get('/vehicles/create', function (Request $request) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->create();
        })->name('vehicles.create');

        Route::post('/vehicles', function (Request $request) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->store($request);
        })->name('vehicles.store');

        Route::get('/vehicles/{vehicle}/edit', function (Request $request, $vehicle) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->edit(\App\Models\Vehicles::findOrFail($vehicle));
        })->name('vehicles.edit');

        Route::patch('/vehicles/{vehicle}', function (Request $request, $vehicle) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->update($request, \App\Models\Vehicles::findOrFail($vehicle));
        })->name('vehicles.update');

        Route::delete('/vehicles/{vehicle}', function (Request $request, $vehicle) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorVehicleController::class)->destroy(\App\Models\Vehicles::findOrFail($vehicle));
        })->name('vehicles.destroy');

        Route::get('/bookings', function (Request $request) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorBookingController::class)->index();
        })->name('bookings.index');

        Route::patch('/bookings/{booking}/confirm', function (Request $request, $booking) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorBookingController::class)->confirm(\App\Models\Bookings::findOrFail($booking));
        })->name('bookings.confirm');

        Route::patch('/bookings/{booking}/reject', function (Request $request, $booking) {
            abort_unless($request->user()?->isVendor(), 403);

            return app(VendorBookingController::class)->reject(\App\Models\Bookings::findOrFail($booking));
        })->name('bookings.reject');
    });

    // ===================== Admin Routes =====================
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

// ===================== Profile Management Routes =====================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================== Authentication Routes =====================

require __DIR__.'/auth.php';