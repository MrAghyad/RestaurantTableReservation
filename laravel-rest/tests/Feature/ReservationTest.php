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
            "starting_time"=> "12:52",
            "ending_time"=> "13:59",
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


}
