<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Listing;
use App\Models\Booking;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/users",
     *     summary="Get all users",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    // Get all users
    public function users()
    {

        // Get all users with their roles, excluding admins
        $users = User::with('roles')
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->orWhereDoesntHave('roles')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => optional($user->roles->first())->name ?? 'user',
                    'created_at' => $user->created_at
                ];
            });

        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/admin/merchants",
     *     summary="Get all merchants",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of merchants",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    // Get all merchants
    public function merchants()
    {
        $merchants = Merchant::with('user.roles')->get()->map(function($merchant) {
            return [
                'id' => $merchant->id,
                'business_name' => $merchant->business_name,
                'category' => $merchant->category,
                'address' => $merchant->address,
                'status' => $merchant->status,
                'created_at' => $merchant->created_at,
                'user' => [
                    'id' => $merchant->user->id,
                    'name' => $merchant->user->name,
                    'email' => $merchant->user->email,
                    'phone' => $merchant->user->phone,
                    'role' => optional($merchant->user->roles->first())->name ?? 'merchant'
                ]
            ];
        });

        return response()->json($merchants);
    }

    /**
     * @OA\Post(
     *     path="/admin/merchants/{id}/approve",
     *     summary="Approve a merchant",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="approved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Merchant approved",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    // Approve / Reject merchant (example: status field can be added)
    public function approveMerchant(Request $request, $merchantId)
    {
        $merchant = Merchant::findOrFail($merchantId);
        $merchant->status = $request->status ?? 'approved'; // default to approved if no status provided
        $merchant->save();

        return response()->json(['message' => 'Merchant status updated', 'merchant' => $merchant]);
    }

    /**
     * @OA\Get(
     *     path="/admin/listings",
     *     summary="Get all listings",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of listings",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function listings()
    {
        $listings = Listing::with('merchant.user')->get();
        return response()->json($listings);
    }

    /**
     * @OA\Get(
     *     path="/admin/bookings",
     *     summary="Get all bookings",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    // Get all bookings
    public function bookings()
    {
        $bookings = Booking::with('user','listing','ticket')->get();
        return response()->json($bookings);
    }

    /**
     * @OA\Get(
     *     path="/admin/analytics",
     *     summary="Get analytics data",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Analytics data",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
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
