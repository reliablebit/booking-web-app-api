<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Listing;
use App\Models\Booking;

class RandomizedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear only the data we are seeding (optional, comment if not desired)
        // User::truncate();
        // Merchant::truncate();
        // Listing::truncate();
        // Booking::truncate();

        $password = Hash::make('12345678');
        $faker = \Faker\Factory::create();

        // 1. Create 20 users and assign 'user' role
        $users = collect();
        for ($i = 0; $i < 20; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->unique()->phoneNumber(),
                'password' => $password,
            ]);
            $user->assignRole('user');
            $users->push($user);
        }

        // 2. Create 20 merchants (each linked to a random user)
        $merchants = collect();
        for ($i = 0; $i < 20; $i++) {
            $merchants->push(Merchant::create([
                'user_id' => $users->random()->id,
                'business_name' => $faker->company(),
                'category' => $faker->randomElement(['bus','hotel','event','flight']),
                'address' => $faker->address(),
                'status' => $faker->randomElement(['active','inactive','pending']),
            ]));
        }

        // 3. Create 20 listings (each linked to a random merchant)
        $listings = collect();
        for ($i = 0; $i < 20; $i++) {
            $type = $faker->randomElement(['bus','hotel','event','flight']);
            $totalSeats = $faker->numberBetween(10, 100);
            $listings->push(Listing::create([
                'merchant_id' => $merchants->random()->id,
                'title' => $faker->catchPhrase(),
                'type' => $type,
                'price' => $faker->randomFloat(2, 10, 1000),
                'total_seats' => $totalSeats,
                'available_seats' => $totalSeats,
                'start_time' => $faker->dateTimeBetween('+1 days', '+1 year'),
                'location' => $faker->city(),
            ]));
        }

        // 4. Create 20 bookings (each linked to a random user and listing)
        for ($i = 0; $i < 20; $i++) {
            $listing = $listings->random();
            $seatNumber = $faker->numberBetween(1, $listing->total_seats);
            Booking::create([
                'user_id' => $users->random()->id,
                'listing_id' => $listing->id,
                'status' => $faker->randomElement(['pending','confirmed','cancelled']),
                'seat_number' => $seatNumber,
                'booking_ref' => strtoupper(Str::random(10)),
            ]);
        }
    }
}
