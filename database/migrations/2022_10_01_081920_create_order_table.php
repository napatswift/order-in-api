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
        Schema::create('order', function (Blueprint $table) {
            // $table->id();
            // $table->foreignIdFor(\App\Models\Table::class);
            
            $table->char('order_number', 2);
            $table->foreign('order_number')->references('table_number')->on('tables');
            $table->string('order_description');
            $table->timestamps();
        });

        // Schema::table('order', function($table) {
        //     $table->foreign('order_number')->references('table_number')->on('tables');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
};
