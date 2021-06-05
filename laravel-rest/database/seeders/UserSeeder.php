<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => '1234',
            'name' => 'admin_user',
            'role' => 'admin',
            'password' => bcrypt('123456'),
        ]);

        DB::table('users')->insert([
            'id' => '5678',
            'name' => 'employee_user',
            'role' => 'employee',
            'password' => bcrypt('123456'),
        ]);
    }
}
