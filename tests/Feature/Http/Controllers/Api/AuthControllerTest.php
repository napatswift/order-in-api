<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Employee;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $endPoint = '/api/auth';

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

        $response = $this->postJson($this->endPoint.'/login', [
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

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_employee_login()
    {
        $employee = new Employee();
        $employee->name = fake()->name;
        $employee->username = fake()->userName();
        $employee->email = fake()->email();
        $password_test = 'password';
        $employee->password = bcrypt($password_test);
        $employee->is_manager = false;
        $employee->is_employee = false;
        $employee->save();

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $employee->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_fail_login()
    {
        $response = $this->postJson($this->endPoint.'/login', [
            'username' => 'username',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function test_manager_register()
    {
        $name = fake()->name;
        $username = fake()->userName();
        $email = fake()->email();
        $password_test = 'password';

        $response = $this->postJson($this->endPoint.'/register/manager', [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password_test,
        ]);

        $response->assertStatus(201);

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_manager_registers_employee()
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

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $manager->username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
        $accessToken = $response['access_token'];

        $name = fake()->name;
        $username = fake()->userName();
        $email = fake()->email();
        $password_test = 'password';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson($this->endPoint.'/register/employee', [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password_test,
        ]);

        $response->assertStatus(201);

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $username,
            'password' => $password_test,
        ]);

        $response->assertStatus(200);
    }

    public function test_not_manager_registers_employee()
    {
        $name = fake()->name;
        $username = fake()->userName();
        $email = fake()->email();
        $password_test = 'password';

        $response = $this->postJson(
            $this->endPoint.'/register/employee', [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => $password_test,
        ]);

        $response->assertStatus(401);

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $username,
            'password' => $password_test,
        ]);

        $response->assertStatus(401);
    }

    public function test_employee_registers_customer()
    {

        $manager = new Manager();
        $manager->name = fake()->name();
        $manager->username = fake()->userName();
        $manager->email = fake()->email();
        $password_test = 'password';
        $manager->password = bcrypt($password_test);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = $manager->id;
        $restaurant->save();

        $manager->restaurant()->save($restaurant);

        for ($i=0; $i < 10; $i++) { 
            Table::create([
                'table_number' => 'A'.$i,
                'available' => true,
                'restaurant_id' => $restaurant->id,
            ]);
        }

        $this->assertDatabaseCount('tables', 10);
        
        $employee = new Employee();
        $employee->name = fake()->name();
        $employee->username = fake()->userName();
        $employee->email = fake()->email();
        $employee_password_test = 'password1';
        $employee->password = bcrypt($employee_password_test);
        $employee->is_manager = false;
        $employee->is_employee = true;
        $employee->restaurant()->associate($restaurant);
        $employee->save();
            
        $this->assertDatabaseCount('users', 2);
        $this->assertEquals(Employee::count(), 1);

        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $employee->username,
            'password' => $employee_password_test,
        ]);

        $response->assertStatus(200);

        $employeeAccessToken = $response['access_token'];

        $this->assertTrue(Table::find(1)->available, 'The table should be available');
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $employeeAccessToken,
        ])->postJson($this->endPoint.'/register/customer',['table_id' => 1]);

        $response->assertStatus(201);
        $this->assertFalse(Table::find(1)->available, 'The table should be unavailable');

        
        $response = $this->postJson($this->endPoint.'/login', [
            'username' => $response['customerLogin']['username'],
            'password' => $response['customerLogin']['password'],
        ]);

        $response->assertStatus(200);
    }
}
