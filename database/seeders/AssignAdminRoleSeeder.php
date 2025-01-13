<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Get all users and assign them the admin role
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole('admin');
        }
    }
} 