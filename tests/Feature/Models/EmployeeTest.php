<?php

namespace Tests\Feature\Models;

use App\Models\Employee;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function set_up_restaurant()
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

        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = $manager->id;
        $restaurant->save();
    }

    public function test_add_employee()
    {
        $this->set_up_restaurant();

        $employee = new Employee();
        $employee->name = fake()->name;
        $employee->username = fake()->userName();
        $employee->email = fake()->email();
        $password_test = 'password';
        $employee->password = bcrypt($password_test);
        $employee->is_manager = false;
        $employee->is_employee = true;
        $employee->restaurant_id = Restaurant::first()->id;
        $employee->save();

        $this->assertDatabaseHas('users', [
            'name' => $employee->name,
            'is_employee' => true,
            'is_manager' => false
        ]);
    }
}
