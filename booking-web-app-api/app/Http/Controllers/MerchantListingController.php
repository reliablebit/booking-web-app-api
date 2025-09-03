<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class MerchantListingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/merchant/listings",
     *     summary="Create a new listing",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Morning Bus to City"),
     *             @OA\Property(property="type", type="string", enum={"bus","flight","train"}, example="bus"),
     *             @OA\Property(property="price", type="number", example=25.50),
     *             @OA\Property(property="total_seats", type="integer", example=50),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-09-02T08:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Downtown Station")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listing created",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/merchant/bookings",
     *     summary="Get merchant's bookings",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/merchant/stats",
     *     summary="Get merchant statistics",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Merchant stats",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
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
