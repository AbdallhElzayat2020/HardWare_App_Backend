<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin user
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@amisdab.com',
            'password' => Hash::make('12345678'), // You can modify the password
//            'role' => 'admin',  // Assuming there's a 'role' column to store the role of the user
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Customer user
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@amisdab.com',
            'password' => Hash::make('12345678'), // You can modify the password
//            'role' => 'customer', // Assuming there's a 'role' column to store the role of the user
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
