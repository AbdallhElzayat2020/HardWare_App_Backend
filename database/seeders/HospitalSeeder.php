<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hospitals')->insert([
            ['name' => 'Hospital One', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hospital Two', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hospital Three', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hospital Four', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hospital Five', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
