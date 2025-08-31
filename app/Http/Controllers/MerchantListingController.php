<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantListingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:bus,flight,train',
            'price' => 'required|numeric',
            'total_seats' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'location' => 'required'
        ]);

        $listing = Listing::create([
            'merchant_id' => Auth::user()->merchant->id,
            'title' => $request->title,
            'type' => $request->type,
            'price' => $request->price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,
            'start_time' => $request->start_time,
            'location' => $request->location
        ]);

        return response()->json($listing);
    }
    public function bookings()
    {
        $merchant = Auth::user()->merchant;

        $bookings = $merchant->listings()
            ->with('bookings.user', 'bookings.ticket')
            ->get()
            ->pluck('bookings')
            ->flatten();

        return response()->json($bookings);
    }
    public function stats()
    {
        $merchant = Auth::user()->merchant;

        $listings = $merchant->listings()->with('bookings')->get();

        $totalBookings = $listings->sum(fn($l) => $l->bookings->count());
        $revenue = $listings->sum(fn($l) => $l->bookings->count() * $l->price);

        return response()->json([
            'total_bookings' => $totalBookings,
            'revenue' => $revenue
        ]);
    }

}
