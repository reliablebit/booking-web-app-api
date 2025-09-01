<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SearchController extends Controller
{
    /**
     * Search listings with filters
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|string|in:bus,hotel,event,flight',
            'location' => 'nullable|string',
            'date' => 'nullable|date',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'available_seats' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = Listing::with(['merchant.user']);

        // Filter by category/type
        if ($request->filled('category')) {
            $query->where('type', $request->category);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // Filter by date
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('start_time', $date->toDateString());
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by available seats
        if ($request->filled('available_seats')) {
            $query->where('available_seats', '>=', $request->available_seats);
        }

        // Only show future listings
        $query->where('start_time', '>', now());

        // Order by start time
        $query->orderBy('start_time', 'asc');

        // Pagination
        $perPage = $request->get('per_page', 15);
        $listings = $query->paginate($perPage);

        return response()->json([
            'message' => 'Search results retrieved successfully',
            'data' => $listings->items(),
            'pagination' => [
                'current_page' => $listings->currentPage(),
                'last_page' => $listings->lastPage(),
                'per_page' => $listings->perPage(),
                'total' => $listings->total(),
                'from' => $listings->firstItem(),
                'to' => $listings->lastItem()
            ]
        ]);
    }

    /**
     * Get popular listings
     */
    public function popular()
    {
        $listings = Listing::with(['merchant.user'])
            ->withCount('bookings')
            ->where('start_time', '>', now())
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'message' => 'Popular listings retrieved successfully',
            'data' => $listings
        ]);
    }

    /**
     * Get featured listings by category
     */
    public function featured(Request $request)
    {
        $category = $request->get('category');

        $query = Listing::with(['merchant.user'])
            ->where('start_time', '>', now());

        if ($category) {
            $query->where('type', $category);
        }

        $listings = $query->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return response()->json([
            'message' => 'Featured listings retrieved successfully',
            'data' => $listings
        ]);
    }
}
