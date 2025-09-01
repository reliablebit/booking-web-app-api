<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Support\Facades\Log;

class FraudDetectionService
{
    /**
     * Check for potential fraud in booking
     */
    public function checkBookingFraud($booking, $additionalData = [])
    {
        $riskScore = 0;
        $flags = [];

        // Check user behavior patterns
        $userRisk = $this->checkUserRisk($booking->user_id);
        $riskScore += $userRisk['score'];
        $flags = array_merge($flags, $userRisk['flags']);

        // Check booking patterns
        $bookingRisk = $this->checkBookingPatterns($booking);
        $riskScore += $bookingRisk['score'];
        $flags = array_merge($flags, $bookingRisk['flags']);

        // Check payment patterns
        $paymentRisk = $this->checkPaymentRisk($additionalData);
        $riskScore += $paymentRisk['score'];
        $flags = array_merge($flags, $paymentRisk['flags']);

        // Determine risk level
        $riskLevel = $this->calculateRiskLevel($riskScore);

        $result = [
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'flags' => $flags,
            'recommendation' => $this->getRecommendation($riskLevel),
            'checked_at' => now()
        ];

        // Log high-risk transactions
        if ($riskLevel === 'high') {
            Log::warning('High-risk booking detected', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'risk_data' => $result
            ]);
        }

        return $result;
    }

    /**
     * Check user-specific risk factors
     */
    private function checkUserRisk($userId)
    {
        $user = User::with('roles')->find($userId);
        $score = 0;
        $flags = [];

        // New user risk
        if ($user->created_at->isAfter(now()->subDays(7))) {
            $score += 10;
            $flags[] = 'new_user_account';
        }

        // Check user's booking history
        $userBookings = Booking::where('user_id', $userId)->get();

        // Multiple bookings in short time
        $recentBookings = $userBookings->where('created_at', '>', now()->subHours(1))->count();
        if ($recentBookings > 5) {
            $score += 20;
            $flags[] = 'rapid_booking_pattern';
        }

        // High cancellation rate
        $totalBookings = $userBookings->count();
        $cancelledBookings = $userBookings->where('status', 'cancelled')->count();
        if ($totalBookings > 0 && ($cancelledBookings / $totalBookings) > 0.5) {
            $score += 15;
            $flags[] = 'high_cancellation_rate';
        }

        // Multiple failed payments
        $failedPayments = $userBookings->where('payment_status', 'failed')->count();
        if ($failedPayments > 3) {
            $score += 10;
            $flags[] = 'multiple_payment_failures';
        }

        return ['score' => $score, 'flags' => $flags];
    }

    /**
     * Check booking-specific patterns
     */
    private function checkBookingPatterns($booking)
    {
        $score = 0;
        $flags = [];

        $listing = $booking->listing;

        // Booking very close to event time
        $hoursUntilEvent = now()->diffInHours($listing->start_time);
        if ($hoursUntilEvent < 2) {
            $score += 5;
            $flags[] = 'last_minute_booking';
        }

        // High-value booking
        if ($listing->price > 1000) {
            $score += 5;
            $flags[] = 'high_value_booking';
        }

        // Check for duplicate bookings (same user, same listing)
        $duplicateBookings = Booking::where('user_id', $booking->user_id)
            ->where('listing_id', $booking->listing_id)
            ->where('id', '!=', $booking->id)
            ->count();

        if ($duplicateBookings > 0) {
            $score += 15;
            $flags[] = 'duplicate_booking_attempt';
        }

        // Unusual seat selection pattern
        $userPreviousSeats = Booking::where('user_id', $booking->user_id)
            ->where('listing_id', $booking->listing_id)
            ->pluck('seat_number')
            ->toArray();

        if (!empty($userPreviousSeats) && in_array($booking->seat_number, $userPreviousSeats)) {
            $score += 10;
            $flags[] = 'repeated_seat_selection';
        }

        return ['score' => $score, 'flags' => $flags];
    }

    /**
     * Check payment-related risk factors
     */
    private function checkPaymentRisk($additionalData)
    {
        $score = 0;
        $flags = [];

        // Check IP address patterns
        if (isset($additionalData['ip_address'])) {
            $ip = $additionalData['ip_address'];

            // Check for VPN/Proxy (simplified check)
            if ($this->isVpnOrProxy($ip)) {
                $score += 10;
                $flags[] = 'vpn_or_proxy_usage';
            }

            // Check for unusual geographical location
            if (isset($additionalData['country']) &&
                !in_array($additionalData['country'], ['US', 'CA', 'GB', 'AU'])) {
                $score += 5;
                $flags[] = 'unusual_location';
            }
        }

        // Check payment method
        if (isset($additionalData['payment_method'])) {
            $paymentMethod = $additionalData['payment_method'];

            // Prepaid cards are higher risk
            if (strpos(strtolower($paymentMethod), 'prepaid') !== false) {
                $score += 8;
                $flags[] = 'prepaid_card_usage';
            }
        }

        // Check for multiple payment attempts
        if (isset($additionalData['payment_attempts']) && $additionalData['payment_attempts'] > 3) {
            $score += 12;
            $flags[] = 'multiple_payment_attempts';
        }

        return ['score' => $score, 'flags' => $flags];
    }

    /**
     * Calculate risk level based on score
     */
    private function calculateRiskLevel($score)
    {
        if ($score >= 30) {
            return 'high';
        } elseif ($score >= 15) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Get recommendation based on risk level
     */
    private function getRecommendation($riskLevel)
    {
        switch ($riskLevel) {
            case 'high':
                return 'Block transaction and require manual review';
            case 'medium':
                return 'Additional verification required';
            case 'low':
            default:
                return 'Proceed normally';
        }
    }

    /**
     * Simple VPN/Proxy detection (placeholder)
     */
    private function isVpnOrProxy($ip)
    {
        // In production, use a proper VPN detection service
        // This is a simplified check
        $vpnRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16'
        ];

        foreach ($vpnRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is in range
     */
    private function ipInRange($ip, $range)
    {
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }

    /**
     * Get fraud statistics for admin dashboard
     */
    public function getFraudStats($days = 30)
    {
        // This would typically be stored in a fraud_checks table
        // For now, return placeholder data
        return [
            'total_checks' => 1250,
            'high_risk_detected' => 45,
            'medium_risk_detected' => 180,
            'blocked_transactions' => 23,
            'false_positive_rate' => 2.1,
            'period_days' => $days
        ];
    }
}
