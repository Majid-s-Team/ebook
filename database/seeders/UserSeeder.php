<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        
        $admin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password123'),
            'dob'      => '1990-01-01',
            'description' => 'This is the super admin.',
            'is_active' => true,
        ]);

        $admin->assignRole('admin');

        // User
        $user = User::create([
            'name'     => 'Regular User',
            'email'    => 'user@example.com',
            'password' => Hash::make('password123'),
            'dob'      => '2000-01-01',
            'description' => 'This is a regular user.',
            'is_active' => true,
        ]);

        $user->assignRole('user');
    }
}
