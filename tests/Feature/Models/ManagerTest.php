<?php

namespace Tests\Feature\Models;

use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_manager_without_restaurant()
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

        $this->assertDatabaseCount('users', 1);
    }

    public function test_add_manager_with_a_restaurant()
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

        $this->assertDatabaseCount('users', 1);

        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = $manager->id;
        $restaurant->save();

        $manager->restaurant()->save($restaurant);

        $this->assertDatabaseCount('restaurants', 1);

        $this->assertEquals(
            $manager->restaurant->name,
            $restaurant->name,
        );
    }
}
