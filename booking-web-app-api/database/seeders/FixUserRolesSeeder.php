<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Merchant;
use Spatie\Permission\Models\Role;

class FixUserRolesSeeder extends Seeder
{
    public function run(): void
    {

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'merchant', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        // Get all users without roles
        $usersWithoutRoles = User::doesntHave('roles')->get();

        foreach ($usersWithoutRoles as $user) {
            // Check if user has a merchant profile
            $merchant = Merchant::where('user_id', $user->id)->first();

            if ($merchant) {
                // Assign merchant role
                $user->assignRole('merchant');
                echo "Assigned merchant role to: " . $user->email . "\n";
            } else {
                // Assign user role
                $user->assignRole('user');
                echo "Assigned user role to: " . $user->email . "\n";
            }
        }

        echo "Fixed roles for " . $usersWithoutRoles->count() . " users.\n";
    }
}
