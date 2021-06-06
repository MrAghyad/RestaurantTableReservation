<?php

namespace Tests\Feature;

use Database\Seeders\RestaurantTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use App\Models\RestaurantTable;
use Database\Seeders\ReservationSeeder;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function seedRestaurantTables()
    {
        // Run the DatabaseSeeder...
        $this->seed();

        // Run a user seeder...
        $this->seed(RestaurantTableSeeder::class);
    }

    private function seedUsers()
    {
        // Run the DatabaseSeeder...
        $this->seed();

        // Run a user seeder...
        $this->seed(UserSeeder::class);
    }

    private function seedResevations()
    {
        // Run the DatabaseSeeder...
        $this->seed();

        // Run a user seeder...
        $this->seed(ReservationSeeder::class);
    }
}
