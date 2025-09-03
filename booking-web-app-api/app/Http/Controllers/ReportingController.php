<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Listing;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    /**
     * Export users to CSV
     */
    public function exportUsers(Request $request)
    {
        $query = User::with('roles');

        // Apply filters
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Created At'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->phone,
                optional($user->roles->first())->name ?? 'user',
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->downloadCSV($csvData, 'users_export.csv');
    }

    /**
     * Export bookings to CSV
     */
    public function exportBookings(Request $request)
    {
        $query = Booking::with(['user', 'listing.merchant']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('merchant_id')) {
            $query->whereHas('listing', function($q) use ($request) {
                $q->where('merchant_id', $request->merchant_id);
            });
        }

        $bookings = $query->get();

        $csvData = [];
        $csvData[] = [
            'Booking ID', 'Booking Ref', 'User Name', 'User Email',
            'Listing Title', 'Merchant', 'Status', 'Seat Number',
            'Price', 'Created At'
        ];

        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->id,
                $booking->booking_ref,
                $booking->user->name,
                $booking->user->email,
                $booking->listing->title,
                $booking->listing->merchant->business_name,
                $booking->status,
                $booking->seat_number,
                $booking->listing->price,
                $booking->created_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->downloadCSV($csvData, 'bookings_export.csv');
    }

    /**
     * Export merchants to CSV
     */
    public function exportMerchants(Request $request)
    {
        $query = Merchant::with('user');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $merchants = $query->get();

        $csvData = [];
        $csvData[] = [
            'Merchant ID', 'Business Name', 'Category', 'Status',
            'Owner Name', 'Owner Email', 'Address', 'Created At'
        ];

        foreach ($merchants as $merchant) {
            $csvData[] = [
                $merchant->id,
                $merchant->business_name,
                $merchant->category,
                $merchant->status,
                $merchant->user->name,
                $merchant->user->email,
                $merchant->address,
                $merchant->created_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->downloadCSV($csvData, 'merchants_export.csv');
    }

    /**
     * Export listings to CSV
     */
    public function exportListings(Request $request)
    {
        $query = Listing::with(['merchant.user']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('merchant_id')) {
            $query->where('merchant_id', $request->merchant_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->date_to);
        }

        $listings = $query->get();

        $csvData = [];
        $csvData[] = [
            'Listing ID', 'Title', 'Type', 'Price', 'Total Seats',
            'Available Seats', 'Location', 'Start Time', 'Merchant', 'Created At'
        ];

        foreach ($listings as $listing) {
            $csvData[] = [
                $listing->id,
                $listing->title,
                $listing->type,
                $listing->price,
                $listing->total_seats,
                $listing->available_seats,
                $listing->location,
                $listing->start_time->format('Y-m-d H:i:s'),
                $listing->merchant->business_name,
                $listing->created_at->format('Y-m-d H:i:s')
            ];
        }

        return $this->downloadCSV($csvData, 'listings_export.csv');
    }

    /**
     * Generate revenue report
     */
    public function revenueReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $revenueData = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->join('merchants', 'listings.merchant_id', '=', 'merchants.id')
            ->where('bookings.status', 'confirmed')
            ->whereBetween('bookings.created_at', [$dateFrom, $dateTo])
            ->select(
                'merchants.business_name',
                'merchants.category',
                DB::raw('COUNT(bookings.id) as total_bookings'),
                DB::raw('SUM(listings.price) as total_revenue'),
                DB::raw('AVG(listings.price) as avg_booking_value')
            )
            ->groupBy('merchants.id', 'merchants.business_name', 'merchants.category')
            ->orderBy('total_revenue', 'desc')
            ->get();

        if ($request->get('export') === 'csv') {
            $csvData = [];
            $csvData[] = [
                'Business Name', 'Category', 'Total Bookings',
                'Total Revenue', 'Average Booking Value'
            ];

            foreach ($revenueData as $row) {
                $csvData[] = [
                    $row->business_name,
                    $row->category,
                    $row->total_bookings,
                    number_format($row->total_revenue, 2),
                    number_format($row->avg_booking_value, 2)
                ];
            }

            return $this->downloadCSV($csvData, 'revenue_report.csv');
        }

        return response()->json([
            'message' => 'Revenue report generated successfully',
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
            'data' => $revenueData
        ]);
    }

    /**
     * Generate analytics summary report
     */
    public function analyticsSummary(Request $request)
    {
        $days = $request->get('days', 30);
        $dateFrom = now()->subDays($days);

        $summary = [
            'users' => [
                'total' => User::count(),
                'new_this_period' => User::where('created_at', '>=', $dateFrom)->count(),
                'by_role' => User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->select('roles.name', DB::raw('COUNT(*) as count'))
                    ->groupBy('roles.name')
                    ->pluck('count', 'name')
                    ->toArray()
            ],
            'bookings' => [
                'total' => Booking::count(),
                'new_this_period' => Booking::where('created_at', '>=', $dateFrom)->count(),
                'by_status' => Booking::select('status', DB::raw('COUNT(*) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
                'revenue_this_period' => DB::table('bookings')
                    ->join('listings', 'bookings.listing_id', '=', 'listings.id')
                    ->where('bookings.status', 'confirmed')
                    ->where('bookings.created_at', '>=', $dateFrom)
                    ->sum('listings.price')
            ],
            'merchants' => [
                'total' => Merchant::count(),
                'new_this_period' => Merchant::where('created_at', '>=', $dateFrom)->count(),
                'by_category' => Merchant::select('category', DB::raw('COUNT(*) as count'))
                    ->groupBy('category')
                    ->pluck('count', 'category')
                    ->toArray()
            ],
            'listings' => [
                'total' => Listing::count(),
                'new_this_period' => Listing::where('created_at', '>=', $dateFrom)->count(),
                'by_type' => Listing::select('type', DB::raw('COUNT(*) as count'))
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray()
            ]
        ];

        if ($request->get('export') === 'csv') {
            // Flatten summary data for CSV export
            $csvData = [];
            $csvData[] = ['Metric', 'Category', 'Value'];

            foreach ($summary as $section => $data) {
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $subKey => $subValue) {
                            $csvData[] = [$section, $key . '_' . $subKey, $subValue];
                        }
                    } else {
                        $csvData[] = [$section, $key, $value];
                    }
                }
            }

            return $this->downloadCSV($csvData, 'analytics_summary.csv');
        }

        return response()->json([
            'message' => 'Analytics summary generated successfully',
            'period_days' => $days,
            'data' => $summary
        ]);
    }

    /**
     * Helper method to download CSV
     */
    private function downloadCSV($data, $filename)
    {
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
