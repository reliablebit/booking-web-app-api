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
use App\Http\Controllers\OTPController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingCancellationController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\MerchantStatsController;
use App\Http\Controllers\EnhancedBookingController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::post('users/register', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);
Route::post('merchant/register', [MerchantAuthController::class, 'register']);

// OTP Authentication Routes
Route::prefix('otp')->group(function () {
    Route::post('/send', [OTPController::class, 'sendOTP']);
    Route::post('/verify', [OTPController::class, 'verifyOTP']);
});

// Public Search Routes
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search']);
    Route::get('/popular', [SearchController::class, 'popular']);
    Route::get('/featured', [SearchController::class, 'featured']);
});

// Public Availability Routes
Route::prefix('availability')->group(function () {
    Route::get('/{listingId}', [AvailabilityController::class, 'getAvailability']);
});

// Public QR Verification
Route::post('/bookings/verify-qr', [EnhancedBookingController::class, 'verifyQR']);

// Public Listings
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (All Roles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    // Profile & Authentication
    Route::post('users/logout', [AuthController::class, 'logout']);
    Route::get('users/me', [AuthController::class, 'me']);

    // Enhanced Availability Routes (Authenticated)
    Route::prefix('availability')->group(function () {
        Route::post('/{listingId}/reserve', [AvailabilityController::class, 'reserveSeats']);
        Route::post('/{listingId}/release', [AvailabilityController::class, 'releaseSeats']);
        Route::get('/user/reservations', [AvailabilityController::class, 'getUserReservations']);
    });
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (role:user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:user'])->group(function () {
    // Basic Booking Flow
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::get('/bookings/{id}/ticket', [BookingController::class, 'ticketDownload']);

    // Enhanced Booking Routes
    Route::prefix('bookings')->group(function () {
        Route::post('/enhanced', [EnhancedBookingController::class, 'createBooking']);
        Route::post('/{bookingId}/confirm-payment', [EnhancedBookingController::class, 'confirmBooking']);
        Route::get('/{bookingId}/ticket-info', [EnhancedBookingController::class, 'getBookingWithTicket']);
        Route::post('/{bookingId}/cancel', [BookingCancellationController::class, 'cancelBooking']);
        Route::get('/{listingId}/cancellation-policy', [BookingCancellationController::class, 'getCancellationPolicy']);
    });

    // User Statistics
    Route::get('/stats/user', [MerchantStatsController::class, 'getUserStats']);
});

/*
|--------------------------------------------------------------------------
| MERCHANT ROUTES (role:merchant)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:merchant'])->prefix('merchant')->group(function () {
    // Listing Management
    Route::post('/listings', [MerchantListingController::class, 'store']);
    Route::get('/listings', [MerchantListingController::class, 'index']);
    Route::put('/listings/{id}', [MerchantListingController::class, 'update']);
    Route::delete('/listings/{id}', [MerchantListingController::class, 'destroy']);

    // Booking Management
    Route::get('/bookings', [MerchantListingController::class, 'bookings']);
    Route::get('/bookings/{id}', [MerchantListingController::class, 'showBooking']);

    // Statistics & Analytics
    Route::get('/stats', [MerchantListingController::class, 'stats']);
    Route::get('/dashboard', [MerchantListingController::class, 'dashboard']);
});

// Merchant Stats Routes (Authenticated merchants can view their own stats)
Route::middleware(['auth:api'])->prefix('stats')->group(function () {
    Route::get('/merchant/{merchantId}', [MerchantStatsController::class, 'getStats']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    // User Management
    Route::get('/users', [AdminController::class, 'users']);
    Route::post('/users/{id}/status', [AdminController::class, 'updateUserStatus']);

    // Merchant Management
    Route::get('/merchants', [AdminController::class, 'merchants']);
    Route::post('/merchants/{id}/approve', [AdminController::class, 'approveMerchant']);
    Route::post('/merchants/{id}/status', [AdminController::class, 'updateMerchantStatus']);

    // Listing Management
    Route::get('/listings', [AdminController::class, 'listings']);
    Route::post('/listings/{id}/status', [AdminController::class, 'updateListingStatus']);

    // Booking Management
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::post('/bookings/bulk-cancel', [BookingCancellationController::class, 'bulkCancel']);

    // System Analytics
    Route::get('/analytics', [AdminController::class, 'analytics']);
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});

// Admin Statistics Routes
Route::middleware(['auth:api', 'role:admin'])->prefix('stats')->group(function () {
    Route::get('/global', [MerchantStatsController::class, 'getGlobalStats']);
    Route::get('/system', [MerchantStatsController::class, 'getSystemStats']);
});

// Reporting & Export Routes (Admin only)
Route::middleware(['auth:api', 'role:admin'])->prefix('reports')->group(function () {
    Route::get('/users/export', [ReportingController::class, 'exportUsers']);
    Route::get('/bookings/export', [ReportingController::class, 'exportBookings']);
    Route::get('/merchants/export', [ReportingController::class, 'exportMerchants']);
    Route::get('/listings/export', [ReportingController::class, 'exportListings']);
    Route::get('/revenue', [ReportingController::class, 'revenueReport']);
    Route::get('/analytics-summary', [ReportingController::class, 'analyticsSummary']);
    Route::get('/financial-summary', [ReportingController::class, 'financialSummary']);
    Route::get('/user-activity', [ReportingController::class, 'userActivityReport']);
});
