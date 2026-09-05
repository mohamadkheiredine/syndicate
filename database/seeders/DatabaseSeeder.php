<?php

use Database\Seeders\AdminSeeder;
use Database\Seeders\CmsSettingsSeeder;
use Database\Seeders\ContentRolesPermissionsSeeder;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\FixedSectionsSeeder;
use Database\Seeders\GendersSeeder;
use Database\Seeders\MaintenanceSeeder;
use Database\Seeders\ModelHasRolesSeeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RoleHasPermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\SocialMediaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            CmsSettingsSeeder::class,
            CountriesSeeder::class,
            FixedSectionsSeeder::class,
            GendersSeeder::class,
            MaintenanceSeeder::class,
            RolesSeeder::class,
            ModelHasRolesSeeder::class,
            PermissionsSeeder::class,
            RoleHasPermissionsSeeder::class,
            ContentRolesPermissionsSeeder::class,
            SettingsSeeder::class,
            SocialMediaSeeder::class,
        ]);
    }
}
