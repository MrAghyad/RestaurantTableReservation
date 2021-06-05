<?php

namespace Tests\Feature;

use Database\Seeders\RestaurantTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class RestaurantTableControllerTest extends TestCase
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

#region test_index_listing_tables_with_authorized_user_succeeds

    public function test_index_listing_tables_with_authorized_user_succeeds()
    {
        $this->seedRestaurantTables();
        $this->seedUsers();

        //login as admin
        $baseUrl = Config::get('app.url') . '/api/v1/user/login';

        $id = '1234';
        $password = '123456';

        $response = $this->postJson($baseUrl, [
            'id' => $id,
            'password' => $password
        ]);

        $token = json_decode($response->getContent())->token;

        $baseUrl = Config::get('app.url') . '/api/v1/table';

        $baseUrl = $baseUrl . '?token=' . $token;

        $response = $this->getJson($baseUrl);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'msg', 'tables'
        ]);
    }
#endregion

#region test_index_listing_tables_empty_db_with_authorized_user_returns_no_tables

public function test_index_listing_tables_empty_db_with_authorized_user_returns_no_tables()
{
    $this->seedUsers();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '1234';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/table';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->getJson($baseUrl);

    $response->assertStatus(404)
    ->assertExactJson([
        'msg' => 'No tables were found'
    ]);
}
#endregion

#region test_index_listing_tables_with_unauthorized_user_fails

public function test_index_listing_tables_with_unauthorized_user_fails()
{
    $this->seedRestaurantTables();
    $this->seedUsers();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '5678';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/table';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->getJson($baseUrl);

    $response->assertStatus(401)
    ->assertExactJson([
        'msg'=> 'Unauthorized user'
    ]);
}
#endregion

#region test_store_new_table_with_authorized_user_succeeds

public function test_store_new_table_with_authorized_user_succeeds()
{
    $this->seedRestaurantTables();
    $this->seedUsers();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '1234';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/table';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->postJson($baseUrl,[
        'id' => '4',
        'seats' => 6
    ]);

    $response->assertStatus(201)
    ->assertJsonStructure([
        'msg', 'table'
    ]);
}
#endregion
}
