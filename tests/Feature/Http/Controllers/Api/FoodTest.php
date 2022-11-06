<?php

namespace Tests\Feature\Api;

use App\Models\Food;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FoodTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all_food()
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

        Food::factory(10)->create();

        $login_response = $this->postJson('/api/auth/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $login_response->assertStatus(200);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->getJson(
                '/api/foods',
        );

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 10)
        );
    }

    public function test_get_food_with_categories()
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

        Food::factory(10)->create();

        $login_response = $this->postJson('/api/auth/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);

        $login_response->assertStatus(200);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->getJson(
                '/api/foods?relations=categories',
        );

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 10)
                    ->has('data.0.categories', 0)
        );
    }



    public function test_manager_with_no_restaurant_add_food()
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

        $login_response = $this->postJson('/api/auth/login', [
            'username' => $manager->username,
            'password' => $password_test,
        ]);
    
        $image = UploadedFile::fake()->image('image.jpg');

        $login_response->assertStatus(200);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->postJson(
                '/api/foods',
                [
                    'food_name' => 'food',
                    'food_price' => 30,
                    'food_detail' => 'this is a food and you can eat it',
                    'cooking_time' => 5,
                    'category_ids' => [3, 1],
                    'image' => $image,
                ]
            );

        $response
            ->assertStatus(400);

    }

    public function test_manager_with_restaurant_add_food()
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
        
        $login_response = $this->postJson('/api/auth/login', [
            'username' => $manager->username,
            'password' => $password_test,
        ]);
        
        $login_response->assertStatus(200);

        $image = UploadedFile::fake()->image('image.jpg');
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->postJson(
                '/api/foods',
                [
                    'food_name' => 'food',
                    'food_price' => 30,
                    'food_detail' => 'this is a food and you can eat it',
                    'cooking_time' => 5,
                    'category_ids' => [3, 1],
                    'image' => $image,
                    // 'food_allery_ids' => ['sometimes', 'array']
                ]
            );

        $response
            ->assertStatus(201);

    }

    public function test_manager_with_restaurant_add_incomplete_food_request()
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
        
        $login_response = $this->postJson('/api/auth/login', [
            'username' => $manager->username,
            'password' => $password_test,
        ]);
        
        $login_response->assertStatus(200);
        
        $image = UploadedFile::fake()->image('image.jpg');
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->postJson(
                '/api/foods',
                [
                    'food_name' => 'food',
                    'food_price' => 30,
                    'food_detail' => 'this is a food and you can eat it',
                    'cooking_time' => 5,
                ]
            );

        $response
            ->assertStatus(422);
        

    }

    public function test_no_permision_user_add_food()
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
        
        $login_response = $this->postJson('/api/auth/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);
        
        $login_response->assertStatus(200);
        
        $image = UploadedFile::fake()->image('image.jpg');
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $login_response['access_token'],
            ])->postJson(
                '/api/foods',
                [
                    'food_name' => 'food',
                    'food_price' => 30,
                    'food_detail' => 'this is a food and you can eat it',
                    'cooking_time' => 5,
                    'category_ids' => [3, 1],
                    'image' => $image,
                ]
            );

        $response
            ->assertStatus(401);

    }

}
