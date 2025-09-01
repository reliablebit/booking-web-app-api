<?php

// Additional routes for new features - add these to your existing routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingCancellationController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\MerchantStatsController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\EnhancedBookingController;

// OTP Authentication Routes
Route::prefix('otp')->group(function () {
    Route::post('/send', [OTPController::class, 'sendOTP']);
    Route::post('/verify', [OTPController::class, 'verifyOTP']);
});

// Search Routes
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search']);
    Route::get('/popular', [SearchController::class, 'popular']);
    Route::get('/featured', [SearchController::class, 'featured']);
});

// Enhanced Availability Routes
Route::prefix('availability')->group(function () {
    Route::get('/{listingId}', [AvailabilityController::class, 'getAvailability']);
    Route::post('/{listingId}/reserve', [AvailabilityController::class, 'reserveSeats'])->middleware('auth:api');
    Route::post('/{listingId}/release', [AvailabilityController::class, 'releaseSeats'])->middleware('auth:api');
    Route::get('/user/reservations', [AvailabilityController::class, 'getUserReservations'])->middleware('auth:api');
});

// Enhanced Booking Routes
Route::middleware('auth:api')->prefix('bookings')->group(function () {
    Route::post('/enhanced', [EnhancedBookingController::class, 'createBooking']);
    Route::post('/{bookingId}/confirm-payment', [EnhancedBookingController::class, 'confirmBooking']);
    Route::get('/{bookingId}/ticket-info', [EnhancedBookingController::class, 'getBookingWithTicket']);
    Route::post('/verify-qr', [EnhancedBookingController::class, 'verifyQR']);
});

// Booking Cancellation Routes
Route::middleware('auth:api')->prefix('bookings')->group(function () {
    Route::post('/{bookingId}/cancel', [BookingCancellationController::class, 'cancelBooking']);
    Route::get('/{listingId}/cancellation-policy', [BookingCancellationController::class, 'getCancellationPolicy']);
    Route::post('/bulk-cancel', [BookingCancellationController::class, 'bulkCancel']); // Admin only
});

// Merchant Stats Routes
Route::middleware('auth:api')->prefix('stats')->group(function () {
    Route::get('/merchant/{merchantId}', [MerchantStatsController::class, 'getStats']);
    Route::get('/global', [MerchantStatsController::class, 'getGlobalStats']); // Admin only
});

// Reporting & Export Routes (Admin only)
Route::middleware('auth:api')->prefix('reports')->group(function () {
    Route::get('/users/export', [ReportingController::class, 'exportUsers']);
    Route::get('/bookings/export', [ReportingController::class, 'exportBookings']);
    Route::get('/merchants/export', [ReportingController::class, 'exportMerchants']);
    Route::get('/listings/export', [ReportingController::class, 'exportListings']);
    Route::get('/revenue', [ReportingController::class, 'revenueReport']);
    Route::get('/analytics-summary', [ReportingController::class, 'analyticsSummary']);
});
