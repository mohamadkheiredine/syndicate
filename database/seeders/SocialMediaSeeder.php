<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('icon' => '/storage/social-medias/facebook.png','title' => 'Facebook','link' => 'http://facebook.com','pos' => '1','publish' => '1','created_at' => now(),'updated_at' => now()),
            array('icon' => '/storage/social-medias/instagram.png','title' => 'Instagram','link' => 'http://instagram.com','pos' => '2','publish' => '1','created_at' => now(),'updated_at' => now()),
            array('icon' => '/storage/social-medias/linkedin.png','title' => 'LinkedIn','link' => 'http://linkedin.com','pos' => '3','publish' => '1','created_at' => now(),'updated_at' => now())
        ];

        foreach ($data as $row) {
            \App\Models\SocialMedia::create($row);
        }
    }
}
