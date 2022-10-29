<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Food;
use App\Models\FoodAllergy;
use App\Models\Manager;
use App\Models\Promotion;
use App\Models\Rating;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimpleRestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::factory(100)->create();

        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = Manager::inRandomOrder()->first()->id;
        $restaurant->save();
        
        Category::factory(5)->create();
        Food::factory(25)->create();
        FoodAllergy::factory(10)->create();
        Promotion::factory(5)->create();

        for ($i = 0; $i < 5; $i++) {
            $table = new Table();
            $table->table_number = ''.($i+1);
            $table->available = rand(0, 100) >= 30;
            $table->restaurant()->associate($restaurant);
            $table->save();
        }

        $food_list = Food::get();

        $customer = Customer::inRandomOrder()->first();

        foreach ($food_list as $food) {
            $rand_max_cat = rand(2, 4);
            $categories = Category::inRandomOrder()
                                ->limit($rand_max_cat)
                                ->get();
            $rand_max_al = rand(0, 3);
            $allergens = FoodAllergy::inRandomOrder()
                                ->limit($rand_max_al)
                                ->get();
            $food->categories()->saveMany(
                $categories
            );

            $food->foodAllergies()->saveMany(
                $allergens
            );
        }

        $rating_names = ['food', 'service', 'time'];

        for ($i=0; $i < 30; $i++) {
            $review = new Review();
            $review->feedback = fake()->realText(100);
            $review->restaurant()->associate($restaurant);
            $review->save();
            
            foreach ($rating_names as $rating_name) {
                $rating = new Rating();
                $rating->name = $rating_name;
                $rating->count = rand(1, 5);
                $rating->review()->associate($review);
                $rating->save();
            }
        }
    }
}
