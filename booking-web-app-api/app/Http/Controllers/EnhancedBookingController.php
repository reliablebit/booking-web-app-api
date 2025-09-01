<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\Ticket;
use App\Services\PaymentService;
use App\Services\BookingLockService;
use App\Services\QRCodeService;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EnhancedBookingController extends Controller
{
    protected $paymentService;
    protected $lockService;
    protected $qrService;
    protected $fraudService;

    public function __construct(
        PaymentService $paymentService,
        BookingLockService $lockService,
        QRCodeService $qrService,
        FraudDetectionService $fraudService
    ) {
        $this->paymentService = $paymentService;
        $this->lockService = $lockService;
        $this->qrService = $qrService;
        $this->fraudService = $fraudService;
    }

    /**
     * Create booking with enhanced features
     */
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|integer|exists:listings,id',
            'seat_number' => 'nullable|integer|min:1',
            'lock_id' => 'nullable|integer|exists:booking_locks,id',
            'payment_method_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        return DB::transaction(function () use ($request, $user) {
            $listing = Listing::findOrFail($request->listing_id);

            // Handle seat selection
            $seatNumber = $request->seat_number;
            if ($request->lock_id) {
                // Use existing lock
                $lock = $this->lockService->getUserLocks($user->id)
                    ->where('id', $request->lock_id)
                    ->first();

                if (!$lock) {
                    return response()->json(['error' => 'Invalid or expired lock'], 400);
                }

                $seatNumber = $lock->seat_number;
            } elseif (!$seatNumber) {
                // Auto-assign seat
                $seatNumber = $this->lockService->findAvailableSeat($listing->id);
                if (!$seatNumber) {
                    return response()->json(['error' => 'No available seats'], 400);
                }
            }

            // Check seat availability
            if (!$this->lockService->isSeatAvailable($listing->id, $seatNumber)) {
                return response()->json(['error' => 'Seat is not available'], 400);
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'listing_id' => $listing->id,
                'status' => 'pending',
                'seat_number' => $seatNumber,
                'booking_ref' => strtoupper(Str::random(10)),
            ]);

            // Run fraud detection
            $fraudCheck = $this->fraudService->checkBookingFraud($booking, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payment_method' => $request->payment_method_id
            ]);

            // Block high-risk transactions
            if ($fraudCheck['risk_level'] === 'high') {
                $booking->update(['status' => 'blocked', 'fraud_score' => $fraudCheck['risk_score']]);
                return response()->json([
                    'error' => 'Transaction blocked for review',
                    'booking_id' => $booking->id,
                    'contact_support' => true
                ], 403);
            }

            // Create payment intent if payment method provided
            $paymentResult = null;
            if ($request->payment_method_id) {
                $paymentResult = $this->paymentService->createPaymentIntent(
                    $listing->price,
                    'usd',
                    [
                        'booking_id' => $booking->id,
                        'booking_ref' => $booking->booking_ref,
                        'user_id' => $user->id
                    ]
                );

                if (!$paymentResult['success']) {
                    $booking->update(['status' => 'payment_failed']);
                    return response()->json([
                        'error' => 'Payment initialization failed',
                        'details' => $paymentResult['error']
                    ], 400);
                }

                $booking->update([
                    'payment_intent_id' => $paymentResult['data']['id'],
                    'payment_status' => 'pending'
                ]);
            }

            // Release any existing locks for this user/listing
            if ($request->lock_id) {
                $this->lockService->releaseLock($request->lock_id);
            } else {
                $this->lockService->releaseLock(null, $user->id, $listing->id, $seatNumber);
            }

            // Update listing available seats
            $listing->decrement('available_seats');

            // Generate QR code for booking
            $qrData = $this->qrService->generateBookingQR($booking);

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking->fresh(),
                'payment' => $paymentResult ? [
                    'client_secret' => $paymentResult['client_secret'],
                    'payment_intent_id' => $paymentResult['data']['id']
                ] : null,
                'qr_code' => $qrData,
                'fraud_check' => [
                    'risk_level' => $fraudCheck['risk_level'],
                    'risk_score' => $fraudCheck['risk_score']
                ]
            ], 201);
        });
    }

    /**
     * Confirm booking after payment
     */
    public function confirmBooking(Request $request, $bookingId)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return DB::transaction(function () use ($request, $bookingId) {
            $booking = Booking::with(['listing', 'user'])->findOrFail($bookingId);

            // Check authorization
            $user = auth('api')->user();
            if ($booking->user_id !== $user->id && !$user->roles->contains('name', 'admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($booking->status !== 'pending') {
                return response()->json(['error' => 'Booking cannot be confirmed'], 400);
            }

            // Confirm payment
            $paymentResult = $this->paymentService->confirmPayment(
                $booking->payment_intent_id,
                $request->payment_method_id
            );

            if (!$paymentResult['success']) {
                $booking->update(['payment_status' => 'failed']);
                return response()->json([
                    'error' => 'Payment confirmation failed',
                    'details' => $paymentResult['error']
                ], 400);
            }

            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'completed',
                'confirmed_at' => now()
            ]);

            // Create ticket
            $ticket = Ticket::create([
                'booking_id' => $booking->id,
                'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
                'qr_code' => null // Will be set below
            ]);

            // Generate QR code for ticket
            $ticketQR = $this->qrService->generateTicketQR($ticket);
            $ticket->update(['qr_code' => $ticketQR['qr_code_path']]);

            return response()->json([
                'message' => 'Booking confirmed successfully',
                'booking' => $booking->fresh(),
                'ticket' => $ticket,
                'qr_code' => $ticketQR
            ]);
        });
    }

    /**
     * Get booking with ticket info
     */
    public function getBookingWithTicket($bookingId)
    {
        $booking = Booking::with(['listing.merchant', 'user', 'ticket'])
            ->findOrFail($bookingId);

        // Check authorization
        $user = auth('api')->user();
        if ($booking->user_id !== $user->id && !$user->roles->contains('name', 'admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Format response with ticket info
        $response = [
            'booking' => $booking,
            'ticket_info' => null
        ];

        if ($booking->ticket) {
            $response['ticket_info'] = [
                'ticket_number' => $booking->ticket->ticket_number,
                'qr_code_url' => $booking->ticket->qr_code ? asset('storage/' . $booking->ticket->qr_code) : null,
                'event_details' => [
                    'title' => $booking->listing->title,
                    'location' => $booking->listing->location,
                    'start_time' => $booking->listing->start_time,
                    'seat_number' => $booking->seat_number
                ]
            ];
        }

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'data' => $response
        ]);
    }

    /**
     * Verify QR code
     */
    public function verifyQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $verification = $this->qrService->verifyQR($request->qr_data);

        return response()->json([
            'message' => 'QR code verification completed',
            'verification' => $verification
        ]);
    }
}
