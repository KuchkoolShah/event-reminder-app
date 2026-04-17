<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Assign only admin role
        $admin->syncRoles([$adminRole]);

        // Create Normal User
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
            ]
        );

        // Assign only user role
        $user->syncRoles([$userRole]);
    }
}
