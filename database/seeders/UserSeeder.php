<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single user
        // User::create([
        //     'name' => 'Sneha Kumari', // Change to the desired name
        //     'email' => 'sneha.dev@gmail.com', // Unique email
        //     'phone' => '6371003968', // Example phone number
        //     'password' => Hash::make('Mig@2408'), // Hash the password
        //     'role' => 1, // Default role (can be adjusted as needed)
        // ]);
    }
}
