<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'promotion_code' => Str::random(8),
            'name' => fake()->realText(16),
            'description' => fake()->realText(64),
            'discount_amount' => rand(10, 500),
            'begin_useable_date' => Carbon::now()->subDays(rand(20, 60)),
            'end_useable_date' => Carbon::now()->addDays(rand(0, 20)),
            'restaurant_id' => Restaurant::inRandomOrder()->first()->id,
        ];
    }
}
