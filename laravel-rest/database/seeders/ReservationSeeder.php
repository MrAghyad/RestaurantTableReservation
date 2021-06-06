<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reservations')->insert([
            "table_id"=> "2",
            "starting_date"=> Carbon::today()->subDay()->setTime(15,55)->format('Y-m-d H:i'),
            "ending_date"=> Carbon::today()->subDay()->setTime(15,59)->format('Y-m-d H:i'),
        ]);

        DB::table('reservations')->insert([
            "table_id"=> "1",
            "starting_date"=> Carbon::today()->setTime(23,55)->format('Y-m-d H:i'),
            "ending_date"=> Carbon::today()->setTime(23,59)->format('Y-m-d H:i'),
        ]);
    }
}
