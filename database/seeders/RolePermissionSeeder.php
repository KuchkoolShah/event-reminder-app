<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ---------- ALL PERMISSIONS ----------
        $permissions = [
            // Permission management
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            // Role management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Event management (renamed to match your pattern)
            'events-list',
            'events-create',
            'events-edit',
            'events-delete',
            'events-show',

            // User management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-assign',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ---------- CREATE ROLES & ASSIGN PERMISSIONS DYNAMICALLY ----------

        // Admin role – gets ALL permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Event manager role – dynamically assign all permissions starting with 'events-'
        $managerRole = Role::firstOrCreate(['name' => 'event-manager']);
        $eventPermissions = Permission::where('name', 'like', 'events-%')->get();
        $managerRole->syncPermissions($eventPermissions);

        // User Manager role – dynamically assign all permissions starting with 'user-'
        $userManagerRole = Role::firstOrCreate(['name' => 'user-manager']);
        $userPermissions = Permission::where('name', 'like', 'user-%')->get();
        $userManagerRole->syncPermissions($userPermissions);
    }
}
