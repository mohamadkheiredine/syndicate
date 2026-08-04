<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('maintenance_mode' => '1', 'title' => 'Coming Soon', 'text' => 'Our website is under construction, follow us for more updates!', 'image' => NULL, 'secret' => 'secretpass', 'updated_at' => now())
        ];

        foreach ($data as $row) {
            \App\Models\Maintenance::create($row);
        }
    }
}
