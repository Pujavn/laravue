<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'adminOne@example.com',
            'password' => Hash::make('password'),
            'address' => '123 Admin Street',
            'gender' => 'Male',
            'status' => 'active',
            'role' => 'admin',  
            'activation_token' => null,  
        ]);

        // Create a regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), 
            'address' => '456 User Avenue',
            'gender' => 'Female',
            'status' => 'active',
            'role' => 'user',
            'activation_token' => null,  
        ]);
    }
}
