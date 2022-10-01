<?php

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
        Schema::create('order_description', function (Blueprint $table) {
            // $table->id();
            // $table->foreignIdFor(\App\Models\Food::class);
            // $table->foreign('order_id')->references('food_id')->on('food');
            // $table->increments('order_id');
            $table->foreignId('food_id')->constrained();
            $table->integer('order_quantity', 2);
            $table->char('order_status', 20);
            $table->string('order_request');
            $table->double('order_price');
            $table->timestamps();
        });

        // Schema::table('order_description', function($table) {
        //     $table->foreign('order_id')->references('food_id')->on('food');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_description');
    }
};
