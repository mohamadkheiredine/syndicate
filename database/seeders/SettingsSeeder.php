<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('android_app' => NULL,'apple_app' => NULL,'title' => 'Title','text' => '<p>Text</p>','android_version' => '1.0.0','force_update_android' => '0','ios_version' => '1.0.0','force_update_ios' => '0','updated_at' => now())
        ];

        foreach ($data as $row) {
            \App\Models\Setting::create($row);
        }
    }
}
