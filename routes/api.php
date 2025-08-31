<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MerchantAuthController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AvailabilityController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// User Auth
Route::post('users/register', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);

// Merchant Auth
Route::post('merchant/register', [MerchantAuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| User Routes (JWT protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    // Profile
    // Route::get('users/me', [AuthController::class, 'me']);
    Route::post('users/logout', [AuthController::class, 'logout']);

    // Availability check (all authenticated roles can check)
    Route::get('/availability/{listingId}', [AvailabilityController::class, 'show']);
});

// User Booking Flow (role:user)
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/search', [ListingController::class, 'search']);

    // Booking lifecycle
    Route::post('/bookings', [BookingController::class, 'store']);                   // HOLD seat
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm']);   // CONFIRM booking
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);     // CANCEL booking
    Route::get('/bookings/{id}', [BookingController::class, 'show']);               // Booking detail
    Route::get('/bookings/{id}/ticket', [BookingController::class, 'ticketDownload']); // Ticket QR
});

/*
|--------------------------------------------------------------------------
| Merchant Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::post('/listings', [MerchantListingController::class, 'store']);
    Route::get('/bookings', [MerchantListingController::class, 'bookings']);
    Route::get('/stats', [MerchantListingController::class, 'stats']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/merchants', [AdminController::class, 'merchants']);
    Route::post('/merchants/{id}/approve', [AdminController::class, 'approveMerchant']);
    Route::get('/listings', [AdminController::class, 'listings']);
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::get('/analytics', [AdminController::class, 'analytics']);
});
