<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Booking;
use App\Models\BookingLock;
use App\Services\BookingLockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class AvailabilityController extends Controller
{
    protected $lockService;

    public function __construct(BookingLockService $lockService)
    {
        $this->lockService = $lockService;
    }

    /**
     * Get availability for a specific listing (enhanced version)
     */
    public function getAvailability($listingId)
    {
        $listing = Listing::with('merchant')->findOrFail($listingId);

        // Clean expired locks first
        $this->lockService->cleanExpiredLocks();

        // Get booked seats
        $bookedSeats = $listing->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->pluck('seat_number')
            ->toArray();

        // Get locked seats (active locks)
        $lockedSeats = BookingLock::where('listing_id', $listingId)
            ->where('status', 'held')
            ->where('expires_at', '>', now())
            ->pluck('seat_number')
            ->toArray();

        // Calculate available seats
        $totalSeats = $listing->total_seats;
        $occupiedSeats = array_unique(array_merge($bookedSeats, $lockedSeats));
        $availableSeats = [];

        for ($seatNum = 1; $seatNum <= $totalSeats; $seatNum++) {
            if (!in_array($seatNum, $occupiedSeats)) {
                $availableSeats[] = $seatNum;
            }
        }

        $availableCount = count($availableSeats);

        // Generate seat map
        $seatMap = [];
        for ($seatNum = 1; $seatNum <= $totalSeats; $seatNum++) {
            $status = 'available';
            if (in_array($seatNum, $bookedSeats)) {
                $status = 'booked';
            } elseif (in_array($seatNum, $lockedSeats)) {
                $status = 'locked';
            }

            $seatMap[] = [
                'seat_number' => $seatNum,
                'status' => $status
            ];
        }

        return response()->json([
            'message' => 'Availability retrieved successfully',
            'listing' => [
                'id' => $listing->id,
                'title' => $listing->title,
                'type' => $listing->type,
                'price' => $listing->price,
                'start_time' => $listing->start_time,
                'location' => $listing->location
            ],
            'availability' => [
                'total_seats' => $totalSeats,
                'available_count' => $availableCount,
                'booked_count' => count($bookedSeats),
                'locked_count' => count($lockedSeats),
                'available_seats' => $availableSeats,
                'seat_map' => $seatMap
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/availability/{listingId}",
     *     summary="Get listing availability",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="listingId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Availability information",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    // Original method kept for backward compatibility
    public function show($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        // Clean up expired holds
        BookingLock::where('listing_id', $listingId)
            ->where('status', 'held')
            ->where('expires_at', '<', now())
            ->update(['status' => 'released']);

        // Seats confirmed
        $confirmedCount = Booking::where('listing_id', $listingId)
            ->where('status', 'confirmed')->count();

        // Seats currently on hold (not expired)
        $heldCount = BookingLock::where('listing_id', $listingId)
            ->where('status', 'held')
            ->where('expires_at', '>', now())
            ->count();

        $total = $listing->total_seats ?? 0;
        $available = max(0, $total - $confirmedCount - $heldCount);

        return response()->json([
            'listing_id'       => $listingId,
            'total_seats'      => $total,
            'confirmed'        => $confirmedCount,
            'held'             => $heldCount,
            'available'        => $available,
            'start_time'       => $listing->start_time,
            'location'         => $listing->location,
            'price'            => $listing->price,
            'type'             => $listing->type,
        ]);
    }

    /**
     * Reserve seats temporarily (acquire locks)
     */
    public function reserveSeats(Request $request, $listingId)
    {
        $request->validate([
            'seat_numbers' => 'nullable|array',
            'seat_numbers.*' => 'integer|min:1',
            'quantity' => 'nullable|integer|min:1|max:10'
        ]);

        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $listing = Listing::findOrFail($listingId);
        $results = [];
        $successCount = 0;

        // If specific seats requested
        if ($request->filled('seat_numbers')) {
            foreach ($request->seat_numbers as $seatNumber) {
                $lockResult = $this->lockService->acquireLock($listingId, $user->id, $seatNumber);
                $results[] = [
                    'seat_number' => $seatNumber,
                    'success' => $lockResult['success'],
                    'message' => $lockResult['message'] ?? null,
                    'lock_id' => $lockResult['lock']->id ?? null,
                    'expires_at' => $lockResult['expires_at'] ?? null
                ];

                if ($lockResult['success']) {
                    $successCount++;
                }
            }
        } else {
            // Auto-assign seats based on quantity
            $quantity = $request->get('quantity', 1);

            for ($i = 0; $i < $quantity; $i++) {
                $lockResult = $this->lockService->acquireLock($listingId, $user->id);

                if ($lockResult['success']) {
                    $results[] = [
                        'seat_number' => $lockResult['lock']->seat_number,
                        'success' => true,
                        'lock_id' => $lockResult['lock']->id,
                        'expires_at' => $lockResult['expires_at']
                    ];
                    $successCount++;
                } else {
                    $results[] = [
                        'success' => false,
                        'message' => $lockResult['message']
                    ];
                    break; // Stop trying if we can't get seats
                }
            }
        }

        return response()->json([
            'message' => "Reserved {$successCount} seat(s) successfully",
            'listing_id' => $listingId,
            'reserved_count' => $successCount,
            'results' => $results,
            'lock_timeout_minutes' => BookingLockService::LOCK_TIMEOUT_MINUTES
        ]);
    }
}
