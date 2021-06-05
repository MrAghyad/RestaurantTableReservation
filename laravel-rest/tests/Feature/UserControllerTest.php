<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

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

#region test_login_correct_user_credentials_succeeds
    public function correct_user_credentials()
    {
        return [
            ['1234', '123456'], //admin
            ['5678', '123456'], //employee
        ];
    }

    /**
     * @dataProvider correct_user_credentials
     */
    public function test_login_correct_user_credentials_succeeds($id, $password)
    {

        $baseUrl = Config::get('app.url') . '/api/v1/user/login';


        $response = $this->postJson($baseUrl, [
            'id' => $id,
            'password' => $password
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'msg', 'token'
        ]);
    }
#endregion

#region test_login_incorrect_user_credentials_fails
public function incorrect_user_credentials()
{
    return [
        ['1234', '123457'],
        ['3234', '123456'],
        ['3234', '123423'],
    ];
}
/**
 * @dataProvider incorrect_user_credentials
 */
public function test_login_incorrect_user_credentials_fails($id , $password)
{
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'msg' => 'Invalid credentials'
    ]);
}
#endregion

#region test_store_new_user_with_authenticated_account_succeeds
public function test_store_new_user_with_authenticated_account_succeeds()
{
    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '1234';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/user';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->postJson($baseUrl, [
        'id' => '2468',
        'name' => 'Ali',
        'role' => 'employee',
        'password' => bcrypt('123456'),
    ]);

    $response->assertStatus(201)
    ->assertJsonStructure([
        'msg', 'user'
    ]);
}
#endregion

#region test_store_new_user_duplicated_id_with_authenticated_account_fails
public function test_store_new_user_duplicated_id_with_authenticated_account_fails()
{
    //login as admin
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '1234';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/user';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->postJson($baseUrl, [
        'id' => '1234',
        'name' => 'Ali',
        'role' => 'employee',
        'password' => bcrypt('123456'),
    ]);

    $response->assertStatus(400)
    ->assertExactJson([
        'msg' => 'User id is used'
    ]);
}
#endregion

#region  test_store_user_with_unauthorized_account_fails
public function test_store_user_with_unauthorized_account_fails()
{
    //login as employee
    $baseUrl = Config::get('app.url') . '/api/v1/user/login';

    $id = '5678';
    $password = '123456';

    $response = $this->postJson($baseUrl, [
        'id' => $id,
        'password' => $password
    ]);

    $token = json_decode($response->getContent())->token;

    $baseUrl = Config::get('app.url') . '/api/v1/user';

    $baseUrl = $baseUrl . '?token=' . $token;

    $response = $this->postJson($baseUrl, [
        'id' => '2468',
        'name' => 'Ali',
        'role' => 'employee',
        'password' => bcrypt('123456'),
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'msg' => 'Unauthorized user'
    ]);
}
#endregion

#region test_store_user_with_unauthenticated_account_fails
public function test_store_user_with_unauthenticated_account_fails()
{
    $baseUrl = Config::get('app.url') . '/api/v1/user';

    $response = $this->postJson($baseUrl, [
        'id' => '2468',
        'name' => 'Ali',
        'role' => 'employee',
        'password' => bcrypt('123456'),
    ]);

    $response->assertStatus(401)
    ->assertExactJson([
        'message' => 'Unauthenticated.'
    ]);
}
#endregion
}
