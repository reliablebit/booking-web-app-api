<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // Search listings with filters (only from approved merchants)
    public function search(Request $request)
    {
        $query = Listing::whereHas('merchant', function ($q) {
            $q->where('status', 'approved');
        });

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('location')) {
            $query->where('location', 'ILIKE', '%' . $request->location . '%');
        }

        if ($request->has('date')) {
            $query->whereDate('start_time', $request->date);
        }

        $listings = $query->with('merchant')->get();

        return response()->json($listings);
    }
}
