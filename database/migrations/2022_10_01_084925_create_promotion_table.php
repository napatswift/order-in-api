<?php

use App\Models\Restaurant;
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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('promotion_code');
            $table->string('name');
            $table->string('description');
            $table->double('discount_amount');
            // $table->double('max_discount_amount')->nullable(); // for percent discount
            $table->datetime('begin_useable_date'); // 1st day to have promotion
            $table->datetime('end_useable_date'); // last day
            $table->foreignIdFor(Restaurant::class);
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
        Schema::dropIfExists('promotions');
    }
};
