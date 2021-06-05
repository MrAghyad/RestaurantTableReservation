<?php

namespace Tests\Feature;

use Database\Seeders\RestaurantTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use App\Models\RestaurantTable;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

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

    assertNotNull(RestaurantTable::find('4'));
}
#endregion

#region test_store_new_table_duplicated_id_with_authorized_user_fails

public function test_store_new_table_duplicated_id_with_authorized_user_fails()
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
        'id' => '1',
        'seats' => 6
    ]);

    $response->assertStatus(400)
    ->assertExactJson([
        'msg'=> 'Table id is used'
    ]);
}
#endregion

#region test_store_new_table_seats_over_limit_with_authorized_user_fails

public function test_store_new_table_seats_over_limit_with_authorized_user_fails()
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
        'id' => '1',
        'seats' => 13
    ]);

    $response->assertStatus(422)
    ->assertExactJson([
        "errors"=>[
            "seats"=>["The seats must be between 1 and 12."],
        ],
        "message"=>"The given data was invalid."
    ]);
}
#endregion

#region test_store_new_table_with_unauthorized_user_fails

public function test_store_new_table_with_unauthorized_user_fails()
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

    $response = $this->postJson($baseUrl,[
        'id' => '4',
        'seats' => 6
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'msg'=> 'Unauthorized user'
    ]);
}
#endregion

#region test_store_new_table_with_unauthenticated_user_fails

public function test_store_new_table_with_unauthenticated_user_fails()
{
    $this->seedRestaurantTables();
    $this->seedUsers();


    $baseUrl = Config::get('app.url') . '/api/v1/table';

    $response = $this->postJson($baseUrl,[
        'id' => '4',
        'seats' => 6
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'message'=> 'Unauthenticated.'
    ]);
}
#endregion

#region test_destroy_table_with_authorized_user_succeeds

public function test_destroy_table_with_authorized_user_succeeds()
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

    $table_id = '1';
    $baseUrl = $baseUrl . '/'. $table_id;

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->deleteJson($baseUrl);

    $response->assertStatus(200)
    ->assertExactJson([
        'msg'=>'Table deleted'
    ]);

    assertNull(RestaurantTable::find($table_id));
}
#endregion

}
