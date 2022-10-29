<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Food;
use App\Models\FoodAllergy;
use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderDescription;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Rating;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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

        $employees = Employee::get();

        foreach ($employees as $employee) {
            $employee->restaurant()->associate($restaurant);
            $employee->save();
        }

        $customers = Customer::inRandomOrder()->get();

        foreach ($customers as $customer) {
            $customer->restaurant()->associate($restaurant);
            $customer->save();
        }

        for ($i = 0; $i < 5; $i++) {
            $table = new Table();
            $table->table_number = ''.($i+1);
            $table->available = rand(0, 100) >= 30;
            $table->restaurant()->associate($restaurant);
            $table->save();
        }

        $food_list = Food::get();

        foreach ($food_list as $food) {
            $rand_max_cat = rand(2, 4);
            $categories = Category::inRandomOrder()
                                ->limit($rand_max_cat)
                                ->get();

            $rand_max_al = rand(0, 3);
            $allergens = FoodAllergy::inRandomOrder()
                                ->limit($rand_max_al)
                                ->get();

            $rand_max_pr = rand(0, 2);
            $promotions = Promotion::inRandomOrder()
                                ->limit($rand_max_pr)
                                ->get();

            $food->categories()->saveMany($categories);

            $food->foodAllergies()->saveMany($allergens);

            $food->promotion()->saveMany($promotions);
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

        // seed order
        for ($i=0; $i < 100; $i++) {
            $customer = Customer::inRandomOrder()->first();

            $order = new Order();
            $order->restaurant()->associate($restaurant);
            $order->customer()->associate($customer);
            $order->save();
            
            $items_per_order = rand(1, 5);
            for ($j=0; $j < $items_per_order; $j++) { 
                $order_description = new OrderDescription();
                $order_description->order_quantity = rand(1, 5);
                $order_description->order_status = rand(0, 2);
                if (rand(0, 100) < 50)
                    $order_description->order_request = fake()
                        ->realText(rand(50, 100));
                $food = Food::inRandomOrder()->first();
                $order_description->food()->associate($food);
                $order_description->order()->associate($order);
                $order_description->order_price = $food->food_price;
                $order_description->save();
            }
        }

        foreach ($customers as $customer) {
            $payment = new Payment();
            $payment->payment_method = rand(0, 1);
            $payment->date_payment = Carbon::parse($customer->created_at)
                ->addMinutes(rand(40, 80));
            $payment->customer()->associate($customer);
            $payment->save();
        }
    }
}
