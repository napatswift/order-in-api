<?php

use App\Models\Food;
use App\Models\FoodAllergy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_food_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Food::class);
            $table->foreignIdFor(FoodAllergy::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_food_allergy');
    }
};
