<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('restaurant_tables')->insert([
            'id' => '1',
            'seats' => 2,
        ]);

        DB::table('restaurant_tables')->insert([
            'id' => '2',
            'seats' => 4,
        ]);

        DB::table('restaurant_tables')->insert([
            'id' => '3',
            'seats' => 2,
        ]);
    }
}
