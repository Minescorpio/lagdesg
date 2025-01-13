<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // E-commerce permissions
        Permission::create(['name' => 'browse shop']);
        Permission::create(['name' => 'place orders']);
        Permission::create(['name' => 'view own orders']);

        // POS permissions
        Permission::create(['name' => 'access-pos']);
        Permission::create(['name' => 'view-sales']);
        Permission::create(['name' => 'void-sales']);
        Permission::create(['name' => 'view-customers']);
        Permission::create(['name' => 'manage-customers']);
        Permission::create(['name' => 'view-reports']);
        Permission::create(['name' => 'manage-settings']);

        // Product management permissions
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);

        // Category management permissions
        Permission::create(['name' => 'view categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);

        // Create roles and assign permissions
        // Customer role - can only access e-commerce features
        Role::create(['name' => 'customer'])
            ->givePermissionTo([
                'browse shop',
                'place orders',
                'view own orders'
            ]);

        // Cashier role - can only access POS features
        Role::create(['name' => 'cashier'])
            ->givePermissionTo([
                'access-pos',
                'view-sales',
                'view-customers'
            ]);

        // Manager role - can access both e-commerce and POS with limited admin features
        Role::create(['name' => 'manager'])
            ->givePermissionTo([
                'browse shop',
                'place orders',
                'view own orders',
                'access-pos',
                'view-sales',
                'void-sales',
                'view-customers',
                'manage-customers',
                'view-reports',
                'view products',
                'create products',
                'edit products',
                'view categories',
                'create categories',
                'edit categories'
            ]);

        // Admin role - has all permissions
        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());
    }
} 