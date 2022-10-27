<?php

namespace Tests\Feature\Models;

use App\Models\Manager;
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
}
