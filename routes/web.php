<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== Public Routes =====================

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Vehicles Page
Route::get('/vehicles', function () {
    return view('vehicles.index');
})->name('vehicles.index');

// My Bookings Page
Route::get('/my-bookings', function () {
    return view('bookings.index');
})->name('user.bookings');


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