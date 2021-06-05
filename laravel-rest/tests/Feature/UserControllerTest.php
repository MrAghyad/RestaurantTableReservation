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


}
