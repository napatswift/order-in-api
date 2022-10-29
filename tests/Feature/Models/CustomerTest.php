<?php

namespace Tests\Feature\Models;

use App\Models\Customer;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nette\Utils\Random;
use Tests\TestCase;
use Illuminate\Support\Str;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

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

    public function test_add_cutomer()
    {
        $this->set_up_restaurant();

        $customer = new Customer();
        $customer->name = fake()->name;
        $customer->username = Str::random(32);
        $customer->email = Str::random(32);

        $password_test = 'password';
        $customer->password = bcrypt($password_test);

        $customer->is_manager = false;
        $customer->is_employee = false;

        $customer->restaurant_id = Restaurant::first()->id;
        $customer->save();

        $this->assertDatabaseHas('users', [
            'name' => $customer->name,
            'is_employee' => false,
            'is_manager' => false,
        ]);
    }
}
