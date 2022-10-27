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

    public function test_add_manager_without_restaurant()
    {
        $manager = new Manager();
        $manager->name = fake()->name();
        $manager->save();

        $this->assertDatabaseCount('managers', 1);
    }

    public function test_add_manager_with_a_restaurant()
    {
        $manager = new Manager();
        $manager->name = fake()->name();
        $manager->save();

        $this->assertDatabaseCount('managers', 1);

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
