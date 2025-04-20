<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => 'password123',
            'roles' => ['manager'],
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => 'password123',
            'roles' => ['user'],
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user2@example.com',
            'password' => 'password123',
            'roles' => ['user'],
        ]);
    }
}
