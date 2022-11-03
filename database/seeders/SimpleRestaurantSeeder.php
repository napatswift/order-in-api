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
    }
}
