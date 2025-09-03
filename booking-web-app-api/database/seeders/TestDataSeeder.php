<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Listing;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        User::truncate();
        Merchant::truncate();
        Listing::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Common password for all users
        $password = Hash::make('12345678');

        // Create regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1234567890',
                'password' => $password,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1234567891',
                'password' => $password,
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.j@example.com',
                'phone' => '+1234567892',
                'password' => $password,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.d@example.com',
                'phone' => '+1234567893',
                'password' => $password,
            ],
            [
                'name' => 'Michael Wilson',
                'email' => 'michael.w@example.com',
                'phone' => '+1234567894',
                'password' => $password,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            // Assign 'user' role to regular users
            $user->assignRole('user');
        }

        // Create merchant users
        $merchantUsers = [
            [
                'name' => 'City Bus Services',
                'email' => 'bus@citytransport.com',
                'phone' => '+1987654321',
                'password' => $password,
            ],
            [
                'name' => 'Grand Hotel',
                'email' => 'info@grandhotel.com',
                'phone' => '+1987654322',
                'password' => $password,
            ],
            [
                'name' => 'Event Masters',
                'email' => 'events@masters.com',
                'phone' => '+1987654323',
                'password' => $password,
            ],
            [
                'name' => 'Sky Airlines',
                'email' => 'contact@skyair.com',
                'phone' => '+1987654324',
                'password' => $password,
            ],
        ];

        $merchants = [];
        foreach ($merchantUsers as $merchantUserData) {
            $user = User::create($merchantUserData);
            // Assign 'merchant' role to merchant users
            $user->assignRole('merchant');

            $merchants[] = Merchant::create([
                'user_id' => $user->id,
                'business_name' => $merchantUserData['name'],
                'category' => $this->getCategoryFromBusiness($merchantUserData['name']),
                'address' => $this->generateAddress($merchantUserData['name']),
                'status' => 'approved'
            ]);
        }

        // Create listings for each merchant
        $listings = [];

        // Bus listings
        $busMerchant = $merchants[0];
        $listings[] = [
            'merchant_id' => $busMerchant->id,
            'title' => 'Express Bus to Downtown',
            'type' => 'bus',
            'price' => 25.50,
            'total_seats' => 45,
            'available_seats' => 45,
            'start_time' => Carbon::now()->addDays(1)->setTime(8, 0),
            'location' => 'Central Station'
        ];

        $listings[] = [
            'merchant_id' => $busMerchant->id,
            'title' => 'Coastal Route Shuttle',
            'type' => 'bus',
            'price' => 35.75,
            'total_seats' => 30,
            'available_seats' => 30,
            'start_time' => Carbon::now()->addDays(2)->setTime(10, 30),
            'location' => 'Beach Terminal'
        ];

        // Hotel listings
        $hotelMerchant = $merchants[1];
        $listings[] = [
            'merchant_id' => $hotelMerchant->id,
            'title' => 'Deluxe King Room',
            'type' => 'hotel',
            'price' => 189.99,
            'total_seats' => 10,
            'available_seats' => 10,
            'start_time' => Carbon::now()->addDays(5),
            'location' => '123 Luxury Avenue'
        ];

        $listings[] = [
            'merchant_id' => $hotelMerchant->id,
            'title' => 'Executive Suite',
            'type' => 'hotel',
            'price' => 299.50,
            'total_seats' => 5,
            'available_seats' => 5,
            'start_time' => Carbon::now()->addDays(3),
            'location' => '123 Luxury Avenue'
        ];

        // Event listings
        $eventMerchant = $merchants[2];
        $listings[] = [
            'merchant_id' => $eventMerchant->id,
            'title' => 'Summer Music Festival',
            'type' => 'event',
            'price' => 75.00,
            'total_seats' => 500,
            'available_seats' => 500,
            'start_time' => Carbon::now()->addDays(15)->setTime(18, 0),
            'location' => 'City Park Amphitheater'
        ];

        $listings[] = [
            'merchant_id' => $eventMerchant->id,
            'title' => 'Tech Conference 2023',
            'type' => 'event',
            'price' => 299.99,
            'total_seats' => 200,
            'available_seats' => 200,
            'start_time' => Carbon::now()->addDays(30)->setTime(9, 0),
            'location' => 'Convention Center'
        ];

        // Flight listings
        $flightMerchant = $merchants[3];
        $listings[] = [
            'merchant_id' => $flightMerchant->id,
            'title' => 'New York to London',
            'type' => 'flight',
            'price' => 549.99,
            'total_seats' => 180,
            'available_seats' => 180,
            'start_time' => Carbon::now()->addDays(7)->setTime(14, 30),
            'location' => 'JFK International Airport'
        ];

        $listings[] = [
            'merchant_id' => $flightMerchant->id,
            'title' => 'Los Angeles to Tokyo',
            'type' => 'flight',
            'price' => 899.50,
            'total_seats' => 220,
            'available_seats' => 220,
            'start_time' => Carbon::now()->addDays(10)->setTime(22, 45),
            'location' => 'LAX International Airport'
        ];

        // Create the listings
        foreach ($listings as $listingData) {
            Listing::create($listingData);
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Regular user login: john.doe@example.com / 12345678');
        $this->command->info('Merchant login: bus@citytransport.com / 12345678');
    }

    /**
     * Determine category based on business name
     */
    private function getCategoryFromBusiness($businessName): string
    {
        if (stripos($businessName, 'bus') !== false) return 'bus';
        if (stripos($businessName, 'hotel') !== false) return 'hotel';
        if (stripos($businessName, 'event') !== false) return 'event';
        if (stripos($businessName, 'air') !== false) return 'flight';

        // Default to event if no match
        return 'event';
    }

    /**
     * Generate a realistic address based on business type
     */
    private function generateAddress($businessName): string
    {
        if (stripos($businessName, 'bus') !== false) {
            return '123 Transit Way, Transport City';
        }
        if (stripos($businessName, 'hotel') !== false) {
            return '456 Hospitality Blvd, Resort Town';
        }
        if (stripos($businessName, 'event') !== false) {
            return '789 Venue Street, Eventville';
        }
        if (stripos($businessName, 'air') !== false) {
            return '321 Skyway Avenue, Aviation Center';
        }

        return '100 Main Street, Business District';
    }
}
