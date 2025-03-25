<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all hospital IDs to associate staff with hospitals
        $hospitalIds = DB::table('hospitals')->pluck('id');

        // Insert 5 staff members
        DB::table('staff')->insert([
            [
                'name' => 'Staff Member One',
                'mobile' => '1234567890',
                'verification' => 'verified',
                'mobile_verified_at' => now(),
                'role' => 1, // assuming role 1 is for a specific role like admin or nurse
                'hospital_id' => $hospitalIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Member Two',
                'mobile' => '1234567891',
                'verification' => 'verified',
                'mobile_verified_at' => now(),
                'role' => 2, // assuming role 2 is for a different role
                'hospital_id' => $hospitalIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Member Three',
                'mobile' => '1234567892',
                'verification' => 'verified',
                'mobile_verified_at' => now(),
                'role' => 1, // assuming role 1
                'hospital_id' => $hospitalIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Member Four',
                'mobile' => '1234567893',
                'verification' => 'verified',
                'mobile_verified_at' => now(),
                'role' => 2, // assuming role 2
                'hospital_id' => $hospitalIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Member Five',
                'mobile' => '1234567894',
                'verification' => 'verified',
                'mobile_verified_at' => now(),
                'role' => 1, // assuming role 1
                'hospital_id' => $hospitalIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
