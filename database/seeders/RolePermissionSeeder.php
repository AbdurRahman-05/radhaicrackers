<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $storekeeper = Role::firstOrCreate(['name' => 'storekeeper']);
        $user = Role::firstOrCreate(['name' => 'user']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $accountant = Role::firstOrCreate(['name' => 'accountant']);

        $permissions = [
            'manage users',
            'edit articles',
            'delete articles',
            'view reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $admin->syncPermissions(Permission::all());
        $editor->syncPermissions(['edit articles']);
        $viewer->syncPermissions(['view reports']);
    }
} 