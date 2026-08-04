<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('logo' => NULL,'primary_color' => '#338ef0','updated_at' => now())
        ];

        foreach ($data as $row) {
            \App\Models\CmsSetting::create($row);
        }
    }
}
