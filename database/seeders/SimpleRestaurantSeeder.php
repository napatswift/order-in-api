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
        $manager = new Manager();
        $manager->name = fake()->name;
        $manager->username = 'manager.sample';
        $manager->email = fake()->email();
        $password_test = 'password';
        $manager->password = bcrypt($password_test);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        $restaurant = new Restaurant();
        $restaurant->name = 'Restaurant Test';
        $restaurant->owner_id = Manager::inRandomOrder()->first()->id;
        $restaurant->save();

        $employee = new Employee();
        $employee->name = fake()->name;
        $employee->username = 'employee.sample';
        $employee->email = fake()->email();
        $password_test = 'password';
        $employee->password = bcrypt($password_test);
        $employee->is_manager = false;
        $employee->is_employee = false;
        $employee->restaurant()->associate($restaurant);
        $employee->save();

        $user = new Customer();
        $user->name = fake()->name;
        $user->username = 'customer.sample';
        $user->email = fake()->email();
        $password_test = 'password';
        $user->password = bcrypt($password_test);
        $user->is_manager = false;
        $user->is_employee = false;
        $user->restaurant()->associate($restaurant);
        $user->save();

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
