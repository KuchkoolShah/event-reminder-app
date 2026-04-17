<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'), // Change this!
            ]
        );

        // Find the 'admin' role (lowercase, as created in RolePermissionSeeder)
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Assign role if not already assigned
        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }
    }
}
