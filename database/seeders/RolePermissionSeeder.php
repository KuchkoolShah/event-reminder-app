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

        $permissions = [
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'events-list',
            'events-create',
            'events-edit',
            'events-delete',
            'events-show',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-assign',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Event Manager
        $managerRole = Role::firstOrCreate(['name' => 'event-manager']);
        $managerRole->syncPermissions(
            Permission::where('name', 'like', 'events-%')->get()
        );

        // User Manager
        $userManagerRole = Role::firstOrCreate(['name' => 'user-manager']);
        $userManagerRole->syncPermissions(
            Permission::where('name', 'like', 'user-%')->get()
        );

        // Normal User
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions(['events-show']);
    }
}
