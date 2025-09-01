<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Listing;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Get all users
    public function users()
    {
        $users = User::role('user')->get();
        return response()->json($users);
    }

    // Get all merchants
    public function merchants()
    {
        $merchants = Merchant::with('user')->get();
        return response()->json($merchants);
    }

    // Approve / Reject merchant (example: status field can be added)
    public function approveMerchant(Request $request, $merchantId)
    {
        $merchant = Merchant::findOrFail($merchantId);
        $merchant->status = $request->status; // approved / rejected
        $merchant->save();

        return response()->json(['message' => 'Merchant status updated', 'merchant' => $merchant]);
    }

    // Get all listings
    public function listings()
    {
        $listings = Listing::with('merchant.user')->get();
        return response()->json($listings);
    }

    // Get all bookings
    public function bookings()
    {
        $bookings = Booking::with('user','listing','ticket')->get();
        return response()->json($bookings);
    }

    // Analytics: simple example
    public function analytics()
    {
        $totalUsers = User::role('user')->count();
        $totalMerchants = Merchant::count();
        $totalListings = Listing::count();
        $totalBookings = Booking::count();

        $totalRevenue = Listing::with('bookings')->get()
            ->sum(fn($l) => $l->bookings->count() * $l->price);

        return response()->json([
            'total_users' => $totalUsers,
            'total_merchants' => $totalMerchants,
            'total_listings' => $totalListings,
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue
        ]);
    }
}
