<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ListingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/search",
     *     summary="Search listings",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Listing type",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Location",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of listings",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
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
