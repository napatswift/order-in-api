<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Food;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_add_category()
    {
        // create manager
        $manager = new Manager();
        $manager->name = fake()->name;
        $manager->username = fake()->userName();
        $manager->email = fake()->email();
        $password_test = 'password';
        $manager->password = bcrypt($password_test);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        // create restaurant
        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = $manager->id;
        $restaurant->save();
        
        $thaiFoodCategory = Category::create([
            'name' => 'อาหารไทย',
            'restaurant_id' => $restaurant->id
        ]);
        $notThaiFoodCategory = Category::create([
            'name' => 'อาหารไม่ไทย',
            'restaurant_id' => $restaurant->id
        ]);

        $this->assertDatabaseCount('categories', 2);
    }

    public function test_add_category_for_food()
    {
        // create manager
        $manager = new Manager();
        $manager->name = fake()->name;
        $manager->username = fake()->userName();
        $manager->email = fake()->email();
        $password_test = 'password';
        $manager->password = bcrypt($password_test);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        // create restaurant
        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = $manager->id;
        $restaurant->save();

        // add category
        $thaiFoodCategory = Category::create([
            'name' => 'อาหารไทย',
            'restaurant_id' => $restaurant->id
        ]);

        // if it's added
        $this->assertDatabaseCount('categories', 1);

        $food = new Food();
        $food->food_name = 'Food';
        $food->food_price = 40.0;
        $food->food_detail = 'Lorem ipsum dolor sit amet, consectetur' .
            'adipiscing elit, sed do eiusmod tempor incididunt ut' .
            'labore et dolore magna';
        $food->cooking_time = 5;
        $food->restaurant_id = $restaurant->id;
        $food->save();

        // if it's added
        $this->assertDatabaseCount('food', 1);

        $food->categories()->attach($thaiFoodCategory->id);

        $this->assertEquals(
            $thaiFoodCategory->name,
            $food->categories()->first()->name,
        );
    }
}
