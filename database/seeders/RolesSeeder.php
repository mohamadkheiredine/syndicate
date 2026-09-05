<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'super-admin',
            'guard_name' => 'admin',
        ]);
        Role::create([
            'name' => 'admin',
            'guard_name' => 'admin',
        ]);
        Role::create([
            'name' => 'developer',
            'guard_name' => 'admin',
        ]);
        Role::create([
            'name' => 'management-admin',
            'guard_name' => 'admin',
        ]);
        Role::create([
            'name' => 'content-super-admin',
            'guard_name' => 'admin',
        ]);
        Role::create([
            'name' => 'content-admin',
            'guard_name' => 'admin',
        ]);
    }
}
