<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Listing;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchantStatsController extends Controller
{
    /**
     * Get merchant statistics
     */
    public function getStats(Request $request, $merchantId)
    {
        $merchant = Merchant::with('user')->findOrFail($merchantId);

        // Check authorization
        $user = auth('api')->user();
        if ($merchant->user_id !== $user->id && !$user->roles->contains('name', 'admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $days = $request->get('days', 30);
        $dateFrom = now()->subDays($days);

        $stats = [
            'merchant_info' => [
                'id' => $merchant->id,
                'business_name' => $merchant->business_name,
                'category' => $merchant->category,
                'status' => $merchant->status
            ],
            'listings' => $this->getListingStats($merchantId, $dateFrom),
            'bookings' => $this->getBookingStats($merchantId, $dateFrom),
            'revenue' => $this->getRevenueStats($merchantId, $dateFrom),
            'performance' => $this->getPerformanceStats($merchantId, $dateFrom)
        ];

        return response()->json([
            'message' => 'Merchant statistics retrieved successfully',
            'period_days' => $days,
            'data' => $stats
        ]);
    }

    /**
     * Get listing statistics
     */
    private function getListingStats($merchantId, $dateFrom)
    {
        $totalListings = Listing::where('merchant_id', $merchantId)->count();
        $newListings = Listing::where('merchant_id', $merchantId)
            ->where('created_at', '>=', $dateFrom)
            ->count();

        $activeListings = Listing::where('merchant_id', $merchantId)
            ->where('start_time', '>', now())
            ->count();

        $expiredListings = Listing::where('merchant_id', $merchantId)
            ->where('start_time', '<=', now())
            ->count();

        $listingsByType = Listing::where('merchant_id', $merchantId)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $avgPrice = Listing::where('merchant_id', $merchantId)
            ->avg('price');

        return [
            'total' => $totalListings,
            'new_this_period' => $newListings,
            'active' => $activeListings,
            'expired' => $expiredListings,
            'by_type' => $listingsByType,
            'average_price' => round($avgPrice, 2)
        ];
    }

    /**
     * Get booking statistics
     */
    private function getBookingStats($merchantId, $dateFrom)
    {
        $totalBookings = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->count();

        $newBookings = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.created_at', '>=', $dateFrom)
            ->count();

        $bookingsByStatus = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->select('bookings.status', DB::raw('COUNT(*) as count'))
            ->groupBy('bookings.status')
            ->pluck('count', 'status')
            ->toArray();

        $confirmedBookings = $bookingsByStatus['confirmed'] ?? 0;
        $conversionRate = $totalBookings > 0 ? ($confirmedBookings / $totalBookings) * 100 : 0;

        // Daily booking trends
        $dailyBookings = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.created_at', '>=', $dateFrom)
            ->select(
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function($item) {
                return $item->count;
            })
            ->toArray();

        return [
            'total' => $totalBookings,
            'new_this_period' => $newBookings,
            'by_status' => $bookingsByStatus,
            'conversion_rate' => round($conversionRate, 2),
            'daily_trends' => $dailyBookings
        ];
    }

    /**
     * Get revenue statistics
     */
    private function getRevenueStats($merchantId, $dateFrom)
    {
        $totalRevenue = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.status', 'confirmed')
            ->sum('listings.price');

        $revenueThisPeriod = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.status', 'confirmed')
            ->where('bookings.created_at', '>=', $dateFrom)
            ->sum('listings.price');

        $avgBookingValue = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.status', 'confirmed')
            ->avg('listings.price');

        // Daily revenue trends
        $dailyRevenue = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.status', 'confirmed')
            ->where('bookings.created_at', '>=', $dateFrom)
            ->select(
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('SUM(listings.price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function($item) {
                return floatval($item->revenue);
            })
            ->toArray();

        // Revenue by listing type
        $revenueByType = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->where('bookings.status', 'confirmed')
            ->select('listings.type', DB::raw('SUM(listings.price) as revenue'))
            ->groupBy('listings.type')
            ->pluck('revenue', 'type')
            ->toArray();

        return [
            'total' => round($totalRevenue, 2),
            'this_period' => round($revenueThisPeriod, 2),
            'average_booking_value' => round($avgBookingValue, 2),
            'daily_trends' => $dailyRevenue,
            'by_type' => $revenueByType
        ];
    }

    /**
     * Get performance statistics
     */
    private function getPerformanceStats($merchantId, $dateFrom)
    {
        // Popular listings
        $popularListings = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->where('listings.merchant_id', $merchantId)
            ->select(
                'listings.id',
                'listings.title',
                'listings.type',
                DB::raw('COUNT(bookings.id) as booking_count'),
                DB::raw('SUM(listings.price) as revenue')
            )
            ->groupBy('listings.id', 'listings.title', 'listings.type')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // Occupancy rates
        $occupancyRates = DB::table('listings')
            ->leftJoin('bookings', function($join) {
                $join->on('listings.id', '=', 'bookings.listing_id')
                     ->where('bookings.status', 'confirmed');
            })
            ->where('listings.merchant_id', $merchantId)
            ->select(
                'listings.id',
                'listings.title',
                'listings.total_seats',
                DB::raw('COUNT(bookings.id) as booked_seats'),
                DB::raw('ROUND((COUNT(bookings.id) / listings.total_seats) * 100, 2) as occupancy_rate')
            )
            ->groupBy('listings.id', 'listings.title', 'listings.total_seats')
            ->orderBy('occupancy_rate', 'desc')
            ->get();

        $avgOccupancyRate = $occupancyRates->avg('occupancy_rate');

        // Customer satisfaction (placeholder - would need reviews/ratings)
        $customerSatisfaction = [
            'average_rating' => 4.2, // Placeholder
            'total_reviews' => 156,  // Placeholder
            'response_rate' => 89.5  // Placeholder
        ];

        return [
            'popular_listings' => $popularListings,
            'occupancy_rates' => $occupancyRates,
            'average_occupancy_rate' => round($avgOccupancyRate, 2),
            'customer_satisfaction' => $customerSatisfaction
        ];
    }

    /**
     * Get global merchant statistics (admin only)
     */
    public function getGlobalStats(Request $request)
    {
        $user = auth('api')->user();
        if (!$user->roles->contains('name', 'admin')) {
            return response()->json(['error' => 'Admin access required'], 403);
        }

        $days = $request->get('days', 30);
        $dateFrom = now()->subDays($days);

        $stats = [
            'total_merchants' => Merchant::count(),
            'new_merchants' => Merchant::where('created_at', '>=', $dateFrom)->count(),
            'active_merchants' => Merchant::where('status', 'active')->count(),
            'merchants_by_category' => Merchant::select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
            'top_revenue_merchants' => DB::table('merchants')
                ->join('listings', 'merchants.id', '=', 'listings.merchant_id')
                ->join('bookings', 'listings.id', '=', 'bookings.listing_id')
                ->where('bookings.status', 'confirmed')
                ->select(
                    'merchants.id',
                    'merchants.business_name',
                    'merchants.category',
                    DB::raw('SUM(listings.price) as total_revenue'),
                    DB::raw('COUNT(bookings.id) as total_bookings')
                )
                ->groupBy('merchants.id', 'merchants.business_name', 'merchants.category')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get()
        ];

        return response()->json([
            'message' => 'Global merchant statistics retrieved successfully',
            'period_days' => $days,
            'data' => $stats
        ]);
    }
}
