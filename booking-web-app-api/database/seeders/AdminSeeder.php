<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        // Create admin user (if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // unique check
            [
                'name' => 'Super Admin',
                'phone' => '03000000000',
                'password' => Hash::make('password123')
            ]
        );

    // Assign only the admin role (remove any others)
    $admin->syncRoles(['admin']);
    }
}
