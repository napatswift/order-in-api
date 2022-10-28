<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $endPoint = '/api/auth/login';

    public function test_manager_login()
    {
        $manager = new Manager();
        $manager->name = fake()->name;
        $manager->username = fake()->userName();
        $manager->email = fake()->email();
        $password_test = 'password';
        $manager->password = bcrypt($password_test);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        $response = $this->postJson($this->endPoint, [
            'username' => $manager->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_login()
    {
        $user = new User();
        $user->name = fake()->name;
        $user->username = fake()->userName();
        $user->email = fake()->email();
        $password_test = 'password';
        $user->password = bcrypt($password_test);
        $user->is_manager = false;
        $user->is_employee = false;
        $user->save();

        $response = $this->postJson($this->endPoint, [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_employee_login()
    {
        $user = new User();
        $user->name = fake()->name;
        $user->username = fake()->userName();
        $user->email = fake()->email();
        $password_test = 'password';
        $user->password = bcrypt($password_test);
        $user->is_manager = false;
        $user->is_employee = false;
        $user->save();

        $response = $this->postJson($this->endPoint, [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_fail_login()
    {
        $response = $this->postJson($this->endPoint, [
            'username' => 'username',
            'password' => bcrypt('password'),
        ]);

        $response->assertStatus(401);
    }
}
