<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'food_name' => fake('th_TH')->word(),
            'food_price' => rand(200, 500),
            'food_detail'=> fake()->realText(128),
            'restaurant_id' => rand(1, Restaurant::count()),
            'cooking_time' => rand(1, 15)
        ];
    }
}
