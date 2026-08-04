<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            array('role_id' => '1', 'model_type' => 'App\\Models\\Admin', 'model_id' => '1'),
        ];

        foreach ($data as $row) {
            DB::table('model_has_roles')->insert($row);
        }
    }
}
