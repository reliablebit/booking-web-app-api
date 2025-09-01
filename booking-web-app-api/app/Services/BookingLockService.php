<?php

namespace App\Services;

use App\Models\BookingLock;
use App\Models\Listing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingLockService
{
    const LOCK_TIMEOUT_MINUTES = 15;

    /**
     * Acquire seat lock for booking
     */
    public function acquireLock($listingId, $userId, $seatNumber = null)
    {
        return DB::transaction(function () use ($listingId, $userId, $seatNumber) {
            $listing = Listing::findOrFail($listingId);

            // Clean expired locks first
            $this->cleanExpiredLocks();

            // If no specific seat requested, find available seat
            if (!$seatNumber) {
                $seatNumber = $this->findAvailableSeat($listingId);
                if (!$seatNumber) {
                    return [
                        'success' => false,
                        'message' => 'No available seats'
                    ];
                }
            }

            // Check if seat is already locked or booked
            $existingLock = BookingLock::where('listing_id', $listingId)
                ->where('seat_number', $seatNumber)
                ->where('status', 'held')
                ->where('expires_at', '>', now())
                ->first();

            if ($existingLock) {
                return [
                    'success' => false,
                    'message' => 'Seat is already locked by another user'
                ];
            }

            // Check if seat is already booked
            $isBooked = $listing->bookings()
                ->where('seat_number', $seatNumber)
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();

            if ($isBooked) {
                return [
                    'success' => false,
                    'message' => 'Seat is already booked'
                ];
            }

            // Create lock
            $lock = BookingLock::create([
                'listing_id' => $listingId,
                'user_id' => $userId,
                'seat_number' => $seatNumber,
                'expires_at' => now()->addMinutes(self::LOCK_TIMEOUT_MINUTES),
                'status' => 'held'
            ]);

            return [
                'success' => true,
                'lock' => $lock,
                'expires_at' => $lock->expires_at,
                'timeout_minutes' => self::LOCK_TIMEOUT_MINUTES
            ];
        });
    }

    /**
     * Release seat lock
     */
    public function releaseLock($lockId = null, $userId = null, $listingId = null, $seatNumber = null)
    {
        $query = BookingLock::where('status', 'held');

        if ($lockId) {
            $query->where('id', $lockId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($listingId) {
            $query->where('listing_id', $listingId);
        }

        if ($seatNumber) {
            $query->where('seat_number', $seatNumber);
        }

        $updated = $query->update([
            'status' => 'released'
        ]);

        return [
            'success' => true,
            'released_locks' => $updated
        ];
    }

    /**
     * Clean expired locks
     */
    public function cleanExpiredLocks()
    {
        $expired = BookingLock::where('status', 'held')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'released'
            ]);

        return $expired;
    }

    /**
     * Find available seat for listing
     */
    public function findAvailableSeat($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        // Get all occupied seat numbers (booked + locked)
        $occupiedSeats = collect();

        // Get booked seats
        $bookedSeats = $listing->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->pluck('seat_number');
        $occupiedSeats = $occupiedSeats->merge($bookedSeats);

        // Get locked seats
        $lockedSeats = BookingLock::where('listing_id', $listingId)
            ->where('status', 'held')
            ->where('expires_at', '>', now())
            ->pluck('seat_number');
        $occupiedSeats = $occupiedSeats->merge($lockedSeats);

        // Find first available seat
        for ($seatNum = 1; $seatNum <= $listing->total_seats; $seatNum++) {
            if (!$occupiedSeats->contains($seatNum)) {
                return $seatNum;
            }
        }

        return null;
    }

    /**
     * Get user's active locks
     */
    public function getUserLocks($userId)
    {
        return BookingLock::with(['listing', 'user'])
            ->where('user_id', $userId)
            ->where('status', 'held')
            ->where('expires_at', '>', now())
            ->get();
    }

    /**
     * Check if seat is available
     */
    public function isSeatAvailable($listingId, $seatNumber)
    {
        $listing = Listing::findOrFail($listingId);

        // Check if seat number is valid
        if ($seatNumber > $listing->total_seats || $seatNumber < 1) {
            return false;
        }

        // Check if seat is booked
        $isBooked = $listing->bookings()
            ->where('seat_number', $seatNumber)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($isBooked) {
            return false;
        }

        // Check if seat is locked
        $isLocked = BookingLock::where('listing_id', $listingId)
            ->where('seat_number', $seatNumber)
            ->where('status', 'held')
            ->where('expires_at', '>', now())
            ->exists();

        return !$isLocked;
    }

    /**
     * Extend lock expiration
     */
    public function extendLock($lockId, $additionalMinutes = 10)
    {
        $lock = BookingLock::where('id', $lockId)
            ->where('status', 'held')
            ->first();

        if (!$lock) {
            return [
                'success' => false,
                'message' => 'Lock not found or expired'
            ];
        }

        $lock->expires_at = $lock->expires_at->addMinutes($additionalMinutes);
        $lock->save();

        return [
            'success' => true,
            'lock' => $lock,
            'new_expiration' => $lock->expires_at
        ];
    }
}
