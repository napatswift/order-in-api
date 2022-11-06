<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Food;
use App\Models\FoodAllergy;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function get_access_token_of_customer()
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

        $manager->restaurant()->save($restaurant);

        for ($i=0; $i < 10; $i++) { 
            $table = Table::create([
                'table_number' => 'A'.$i,
                'available' => true,
                'restaurant_id' => $restaurant->id,
            ]);
        }

        $this->assertDatabaseCount('tables', 10);

        Food::factory(30)->create();

        $user = new Customer();
        $user->name = fake()->name;
        $user->username = fake()->userName();
        $user->email = fake()->email();
        $password_test = 'password';
        $user->password = bcrypt($password_test);
        $user->is_manager = false;
        $user->is_employee = false;
        $user->restaurant()->associate($restaurant);
        $user->table()->associate($table);
        $user->save();

        $login_response = $this->postJson('/api/auth/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $login_response->assertStatus(200);

        return $login_response['access_token'];
    }

    public function test_customer_places_new_order()
    {
        $customer_access_token = $this->get_access_token_of_customer();

        $order_items = collect([]);
        $random_food_collection = Food::inRandomOrder()->get();

        for ($i = 0; $i < 10; $i++) {
            if (rand(1, 100) < 50) {
                $order_items->push([
                    'food_id' => $random_food_collection[$i]->id,
                    'order_quantity' => rand(1, 10),
                ]);
            } else {
                $order_items->push([
                    'food_id' => $random_food_collection[$i]->id,
                    'order_quantity' => rand(1, 10),
                    'order_request' => fake()->text(),
                ]);
            }
        }

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $customer_access_token)
            ->postJson('api/orders/place', [
                'order_items' => $order_items
            ]);

        $response->assertStatus(201);
    }

    public function test_customer_places_new_order_invalid_request_content()
    {
        $customer_access_token = $this->get_access_token_of_customer();

        $order_items = collect([]);
        $random_food_collection = Food::inRandomOrder()->get();

        for ($i = 0; $i < 10; $i++) {
            if (rand(1, 100) < 50) {
                $order_items->push([
                    'order_quantity' => rand(1, 10),
                ]);
            } else {
                $order_items->push([
                    'food_id' => $random_food_collection[$i]->id,
                    'order_quantity' => rand(1, 10),
                    'order_request' => fake()->text(),
                ]);
            }
        }

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $customer_access_token)
            ->postJson('api/orders/place', [
                'order_items' => $order_items
            ]);

        $response->assertStatus(422);
    }
}
