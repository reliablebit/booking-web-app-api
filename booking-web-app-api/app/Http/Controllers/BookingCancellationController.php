<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Services\PaymentService;
use App\Services\BookingLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookingCancellationController extends Controller
{
    protected $paymentService;
    protected $lockService;

    public function __construct(PaymentService $paymentService, BookingLockService $lockService)
    {
        $this->paymentService = $paymentService;
        $this->lockService = $lockService;
    }

    /**
     * Cancel booking and process refund
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
            'refund_amount' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return DB::transaction(function () use ($request, $bookingId) {
            $booking = Booking::with(['listing', 'user', 'ticket'])->findOrFail($bookingId);

            // Check if user owns this booking or is admin
            $user = auth('api')->user();
            if ($booking->user_id !== $user->id && !$user->roles->contains('name', 'admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if booking can be cancelled
            if ($booking->status === 'cancelled') {
                return response()->json(['error' => 'Booking is already cancelled'], 400);
            }

            // Check cancellation policy (e.g., 24 hours before event)
            $cancellationDeadline = $booking->listing->start_time->subHours(24);
            if (now()->isAfter($cancellationDeadline)) {
                return response()->json([
                    'error' => 'Cancellation not allowed within 24 hours of event'
                ], 400);
            }

            // Calculate refund amount
            $refundAmount = $this->calculateRefundAmount($booking, $request->refund_amount);

            // Process refund if payment exists
            $refundResult = null;
            if ($booking->payment_intent_id) {
                $refundResult = $this->paymentService->createRefund(
                    $booking->payment_intent_id,
                    $refundAmount,
                    $request->reason ?: 'Customer requested cancellation'
                );

                if (!$refundResult['success']) {
                    return response()->json([
                        'error' => 'Refund processing failed: ' . $refundResult['error']
                    ], 500);
                }
            }

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->reason,
                'cancelled_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_status' => $refundResult ? 'processed' : 'not_applicable'
            ]);

            // Update listing available seats
            $booking->listing->increment('available_seats');

            // Release any active locks for this seat
            $this->lockService->releaseLock(
                null,
                $booking->user_id,
                $booking->listing_id,
                $booking->seat_number
            );

            return response()->json([
                'message' => 'Booking cancelled successfully',
                'booking' => $booking->fresh(),
                'refund_amount' => $refundAmount,
                'refund_status' => $refundResult ? 'processed' : 'not_applicable'
            ]);
        });
    }

    /**
     * Get cancellation policy for a listing
     */
    public function getCancellationPolicy($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        $policy = [
            'cancellation_deadline' => $listing->start_time->subHours(24),
            'full_refund_deadline' => $listing->start_time->subHours(48),
            'partial_refund_deadline' => $listing->start_time->subHours(24),
            'no_refund_after' => $listing->start_time->subHours(24),
            'refund_percentages' => [
                'more_than_48_hours' => 100,
                '24_to_48_hours' => 75,
                'less_than_24_hours' => 0
            ]
        ];

        return response()->json([
            'message' => 'Cancellation policy retrieved successfully',
            'policy' => $policy,
            'listing' => $listing
        ]);
    }

    /**
     * Calculate refund amount based on cancellation policy
     */
    private function calculateRefundAmount($booking, $requestedAmount = null)
    {
        $originalAmount = $booking->listing->price;
        $hoursUntilEvent = now()->diffInHours($booking->listing->start_time);

        // If specific refund amount requested (admin override)
        if ($requestedAmount !== null) {
            return min($requestedAmount, $originalAmount);
        }

        // Apply cancellation policy
        if ($hoursUntilEvent >= 48) {
            return $originalAmount; // 100% refund
        } elseif ($hoursUntilEvent >= 24) {
            return $originalAmount * 0.75; // 75% refund
        } else {
            return 0; // No refund
        }
    }

    /**
     * Bulk cancel bookings (admin only)
     */
    public function bulkCancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'integer|exists:bookings,id',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check admin role
        $user = auth('api')->user();
        if (!$user->roles->contains('name', 'admin')) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($request->booking_ids as $bookingId) {
            try {
                $result = $this->cancelBooking(
                    new Request(['reason' => $request->reason]),
                    $bookingId
                );

                if ($result->getStatusCode() === 200) {
                    $successCount++;
                    $results[] = [
                        'booking_id' => $bookingId,
                        'status' => 'success'
                    ];
                } else {
                    $errorCount++;
                    $results[] = [
                        'booking_id' => $bookingId,
                        'status' => 'error',
                        'message' => 'Cancellation failed'
                    ];
                }
            } catch (\Exception $e) {
                $errorCount++;
                $results[] = [
                    'booking_id' => $bookingId,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'message' => "Bulk cancellation completed. {$successCount} successful, {$errorCount} failed.",
            'results' => $results,
            'summary' => [
                'total' => count($request->booking_ids),
                'successful' => $successCount,
                'failed' => $errorCount
            ]
        ]);
    }
}
