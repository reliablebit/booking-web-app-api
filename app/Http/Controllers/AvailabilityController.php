<?php

// app/Http/Controllers/AvailabilityController.php
namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Booking;
use App\Models\BookingLock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AvailabilityController extends Controller
{
    // Show current availability snapshot for a listing
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
}
