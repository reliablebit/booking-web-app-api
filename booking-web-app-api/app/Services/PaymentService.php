<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.payment.api_key', 'test_key');
        $this->baseUrl = config('services.payment.base_url', 'https://api.stripe.com/v1');
    }

    /**
     * Create payment intent
     */
    public function createPaymentIntent($amount, $currency = 'usd', $metadata = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post($this->baseUrl . '/payment_intents', [
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'client_secret' => $response->json('client_secret')
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Payment failed')
            ];
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Payment service unavailable'
            ];
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment($paymentIntentId, $paymentMethodId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post($this->baseUrl . "/payment_intents/{$paymentIntentId}/confirm", [
                'payment_method' => $paymentMethodId,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Payment confirmation failed')
            ];
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Payment confirmation failed'
            ];
        }
    }

    /**
     * Create refund
     */
    public function createRefund($paymentIntentId, $amount = null, $reason = 'requested_by_customer')
    {
        try {
            $data = [
                'payment_intent' => $paymentIntentId,
                'reason' => $reason,
            ];

            if ($amount) {
                $data['amount'] = $amount * 100; // Convert to cents
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post($this->baseUrl . '/refunds', $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Refund failed')
            ];
        } catch (\Exception $e) {
            Log::error('Refund failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Refund service unavailable'
            ];
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus($paymentIntentId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . "/payment_intents/{$paymentIntentId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Payment status check failed'
            ];
        } catch (\Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Payment service unavailable'
            ];
        }
    }
}
