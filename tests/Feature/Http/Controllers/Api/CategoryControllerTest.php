<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use PHPOpenSourceSaver\JWTAuth\Claims\Custom;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function get_accesstoken_of_manger_with_restaurant()
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
        
        return $login_response['access_token'];
    }

    protected function get_accesstoken_of_customer()
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

        $user = new Customer();
        $user->name = fake()->name;
        $user->username = fake()->userName();
        $user->email = fake()->email();
        $password_test = 'password';
        $user->password = bcrypt($password_test);
        $user->is_manager = false;
        $user->is_employee = false;
        $user->restaurant()->associate($restaurant);
        $user->save();
        
        $login_response = $this->postJson('/api/auth/login', [
            'username' => $user->username,
            'password' => $password_test,
        ]);
        
        $login_response->assertStatus(200);
        
        return $login_response['access_token'];
    }

    protected function get_accesstoken_of_manger_without_restaurant()
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
        
        $login_response->assertStatus(200);
        
        return $login_response['access_token'];
    }

    public function test_manager_add_category()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $accessToken = $this->get_accesstoken_of_manger_with_restaurant();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/categories', [
            'name' => fake()->word(),
            'image' => $image
        ]);

        $response->assertStatus(201);
    }

    public function test_update_category()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $accessToken = $this->get_accesstoken_of_manger_with_restaurant();
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/categories', [
            'name' => fake()->word(),
            'image' => $image
        ]);

        $categoy_id = $response->json('category');


        $response->assertStatus(201);

        $old_name = Category::find($categoy_id)->name;
    
        $new_name = fake()->word();
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/categories/'.$categoy_id, [
            'name' => $new_name,
            'image' => $image
        ]);

        $new_name_from_db = Category::find($categoy_id);

        $response->assertStatus(201);
        $this->assertEquals($new_name, $new_name_from_db->name);
    }

    public function test_delete_category()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $accessToken = $this->get_accesstoken_of_manger_with_restaurant();
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/categories', [
            'name' => fake()->word(),
            'image' => $image
        ]);

        $categoy_id = $response->json('category');
        $response->assertStatus(201);

        $image = UploadedFile::fake()->image('image.jpg');
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/categories/'.$categoy_id,);

        $new_name_from_db = Category::find($categoy_id);

        $response->assertStatus(200);
        $this->assertNull($new_name_from_db);
    }

    public function test_with_no_restaurant_manager_add_category()
    {
        $accessToken = $this->get_accesstoken_of_manger_without_restaurant();
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/categories', [
            'name' => fake()->word(),
            'image' => $image
        ]);

        $response->assertStatus(403);
    }

    public function test_manager_add_category_incomplete_request_data()
    {
        $accessToken = $this->get_accesstoken_of_manger_with_restaurant();
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/categories', [
            'image' => $image
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthorized_user_get_category()
    {
        $response = $this->getJson('/api/categories',);
        
        $response->assertStatus(401);
    }

    public function test_user_get_category()
    {
        $accessToken = $this->get_accesstoken_of_customer();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/categories',);
        
        $response->assertStatus(200);
    }
}
