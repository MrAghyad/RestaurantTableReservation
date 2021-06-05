<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run the DatabaseSeeder...
        $this->seed();

        // Run a user seeder...
        $this->seed(UserSeeder::class);
    }

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
