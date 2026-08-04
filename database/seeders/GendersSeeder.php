<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['name' => 'Male', 'is_active' => true, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Female', 'is_active' => true, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'is_active' => true, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('genders')->insert($genders);
    }
}
