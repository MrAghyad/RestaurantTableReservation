<?php

namespace Tests\Feature;

use Database\Seeders\RestaurantTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Database\Seeders\ReservationSeeder;
use App\Models\Reservation;
use Carbon\Carbon;
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

#region test_get_today_reservations_with_authenticated_user_succeeds
    public function authenticated_user_credentials()
    {
        return [
            ['1234', '123456'], //admin
            ['5678', '123456'], //employee
        ];
    }

    /**
     * @dataProvider authenticated_user_credentials
     */
    public function test_get_today_reservations_with_authenticated_user_succeeds($id, $password)
    {
        $this->seedUsers();
        $this->seedRestaurantTables();
        $this->seedResevations();

        //login as admin
        $baseUrl = Config::get('app.url') . '/api/v1/user/login';

        $response = $this->postJson($baseUrl, [
            'id' => $id,
            'password' => $password
        ]);

        $token = json_decode($response->getContent())->token;

        $baseUrl = Config::get('app.url') . '/api/v1/reservation';

        $baseUrl = $baseUrl . '?token=' . $token;

        $response = $this->getJson($baseUrl);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'msg', 'reservations'
        ]);
    }
#endregion

#region test_get_today_reservations_no_reservations_in_db_with_authenticated_user_fails
    public function test_get_today_reservations_no_reservations_in_db_with_authenticated_user_fails()
    {
        $this->seedUsers();
        $this->seedRestaurantTables();

        //login as admin
        $baseUrl = Config::get('app.url') . '/api/v1/user/login';

        $id = '1234';
        $password = '123456';

        $response = $this->postJson($baseUrl, [
            'id' => $id,
            'password' => $password
        ]);

        $token = json_decode($response->getContent())->token;

        $baseUrl = Config::get('app.url') . '/api/v1/reservation';

        $baseUrl = $baseUrl . '?token=' . $token;

        $response = $this->getJson($baseUrl);

        $response->assertStatus(404)
        ->assertExactJson([
            'msg'=>'No reservations were found'
        ]);
    }
#endregion

#region test_get_today_reservations_with_unauthenticated_user_fails
public function test_get_today_reservations_with_unauthenticated_user_fails()
{
    $this->seedUsers();
    $this->seedRestaurantTables();


    $baseUrl = Config::get('app.url') . '/api/v1/reservation';


    $response = $this->getJson($baseUrl);

    $response->assertStatus(401)
    ->assertExactJson([
        'message'=>'Unauthenticated.'
    ]);
}
#endregion

#region test_store_reservation_with_authenticated_user_succeeds

    public function test_store_reservation_with_authenticated_user_succeeds()
    {
        $this->seedUsers();
        $this->seedRestaurantTables();
        $this->seedResevations();

        //login as admin
        $baseUrl = Config::get('app.url') . '/api/v1/user/login';

        $response = $this->postJson($baseUrl, [
            'id' => '1234',
            'password' => '123456'
        ]);

        $token = json_decode($response->getContent())->token;

        $baseUrl = Config::get('app.url') . '/api/v1/reservation';

        $baseUrl = $baseUrl . '?token=' . $token;

        $response = $this->postJson($baseUrl,[
            "table_id"=> "1",
            "starting_time"=> Carbon::now()->addMinutes(5)->format('H:i'),
            "ending_time"=> Carbon::now()->addMinutes(10)->format('H:i'),
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'msg', 'reservation'
        ]);
    }
#endregion

#region test_store_reservation_in_table_already_reserved_at_time_with_authenticated_user_fails
    public function test_store_reservation_in_table_already_reserved_at_time_with_authenticated_user_fails()
    {
        $this->seedUsers();
        $this->seedRestaurantTables();
        $this->seedResevations();

        //login as admin
        $baseUrl = Config::get('app.url') . '/api/v1/user/login';

        $response = $this->postJson($baseUrl, [
            'id' => '1234',
            'password' => '123456'
        ]);

        $token = json_decode($response->getContent())->token;

        $baseUrl = Config::get('app.url') . '/api/v1/reservation';

        $baseUrl = $baseUrl . '?token=' . $token;

        $response = $this->postJson($baseUrl,[
            "table_id"=> "1",
            "starting_time"=> "23:55",
            "ending_time"=> "23:59",
        ]);

        $response->assertStatus(400)
        ->assertExactJson([
            'msg'=> 'Reservation cannot be created. Table is already reserved at the provided time.'
        ]);
    }
#endregion

#region test_store_reservation__with_unauthenticated_user_fails    
public function test_store_reservation__with_unauthenticated_user_fails()
{
    $this->seedRestaurantTables();
    $this->seedResevations();

    $baseUrl = Config::get('app.url') . '/api/v1/reservation';

    $response = $this->postJson($baseUrl,[
        "table_id"=> "1",
        "starting_time"=> "13:55",
        "ending_time"=> "14:59",
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'message'=> 'Unauthenticated.'
    ]);
}
#endregion

#region test_delete_reservation_with_authenticated_user_succeeds

public function test_delete_reservation_with_authenticated_user_succeeds()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/2';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->deleteJson($baseUrl);

    $response->assertStatus(200)
    ->assertExactJson([
        'msg'=> 'Reservation canceled'
    ]);
}
#endregion

#region test_delete_reservation_not_in_db_with_authenticated_user_fails

public function test_delete_reservation_not_in_db_with_authenticated_user_fails()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/100';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->deleteJson($baseUrl);

    $response->assertStatus(404)
    ->assertExactJson([
        'msg'=> 'Reservation not found!'
    ]);
}
#endregion

#region test_delete_old_reservation_with_authenticated_user_fails

public function test_delete_old_reservation_with_authenticated_user_fails()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/1';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->deleteJson($baseUrl);

    $response->assertStatus(400)
    ->assertExactJson([
        'msg'=> 'Old reservation cannot be cancelled'
    ]);
}
#endregion

#region test_delete_reservation_with_unauthenticated_user_fails

public function test_delete_reservation_with_unauthenticated_user_fails()
{
    $this->seedRestaurantTables();
    $this->seedResevations();


    $baseUrl = Config::get('app.url') . '/api/v1/reservation/1';

    $response = $this->deleteJson($baseUrl);

    $response->assertStatus(401)
    ->assertExactJson([
        'message'=> 'Unauthenticated.'
    ]);
}
#endregion

#region test_get_all_reservations_with_authorized_user_succeeds

public function test_get_all_reservations_with_authorized_user_succeeds()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/all';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->getJson($baseUrl);

    $response->assertStatus(200)
    ->assertJsonStructure([
        'msg', 'reservations'
    ]);
}
#endregion

#region test_get_all_reservations_for_tables_with_authorized_user_succeeds

public function test_get_all_reservations_for_tables_with_authorized_user_succeeds()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/all';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->json('GET', $baseUrl,[
        'tables_ids' => ['1',]
    ]);

    $response->assertStatus(200)
    ->assertJsonStructure([
        'msg', 'reservations'
    ]);
}
#endregion

#region test_get_all_reservations_for_unreserved_tables_with_authorized_user_returns_not_found

public function test_get_all_reservations_for_unreserved_tables_with_authorized_user_returns_not_found()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/all';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->json('GET', $baseUrl,[
        'tables_ids' => ['3',]
    ]);

    $response->assertStatus(404)
    ->assertExactJson([
        'msg'=> 'No reservations were found'
    ]);
}
#endregion

#region test_get_all_reservations_with_unauthorized_user_fails

public function test_get_all_reservations_with_unauthorized_user_fails()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '5678',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/all';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->json('GET', $baseUrl,[
        'tables_ids' => ['3',]
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'msg'=> 'Unauthorized user'
    ]);
}
#endregion

#region test_check_available_seats_with_authenticated_user_succeeds

public function test_check_available_seats_with_authenticated_user_succeeds()
{
    $this->seedUsers();
    $this->seedRestaurantTables();
    $this->seedResevations();

    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'password' => '123456'
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/reservation/available/3';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->getJson($baseUrl);

    $response->assertStatus(200)
    ->assertJsonStructure([
        'msg', 'tables'
    ]);
}
#endregion
}
