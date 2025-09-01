<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for payment gateway integration
    |
    */

    'payment' => [
        'api_key' => env('PAYMENT_API_KEY', 'test_key'),
        'base_url' => env('PAYMENT_BASE_URL', 'https://api.stripe.com/v1'),
        'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS/OTP service
    |
    */

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'twilio'),
        'api_key' => env('SMS_API_KEY', ''),
        'api_secret' => env('SMS_API_SECRET', ''),
        'from_number' => env('SMS_FROM_NUMBER', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for QR code generation
    |
    */

    'qr_code' => [
        'storage_disk' => env('QR_STORAGE_DISK', 'public'),
        'storage_path' => env('QR_STORAGE_PATH', 'qr-codes'),
        'size' => env('QR_CODE_SIZE', 300),
        'error_correction' => env('QR_ERROR_CORRECTION', 'H'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fraud Detection Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for fraud detection service
    |
    */

    'fraud_detection' => [
        'enabled' => env('FRAUD_DETECTION_ENABLED', true),
        'high_risk_threshold' => env('FRAUD_HIGH_RISK_THRESHOLD', 30),
        'medium_risk_threshold' => env('FRAUD_MEDIUM_RISK_THRESHOLD', 15),
        'auto_block_high_risk' => env('FRAUD_AUTO_BLOCK_HIGH_RISK', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Booking Lock Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for seat locking system
    |
    */

    'booking_locks' => [
        'timeout_minutes' => env('BOOKING_LOCK_TIMEOUT', 15),
        'cleanup_interval' => env('BOOKING_LOCK_CLEANUP_INTERVAL', 5),
        'max_locks_per_user' => env('BOOKING_MAX_LOCKS_PER_USER', 10),
    ],
];
