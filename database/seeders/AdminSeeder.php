<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('P@ssw0rd'),
            'timezone' => 'Asia/Beirut',
            'blocked' => '0',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
