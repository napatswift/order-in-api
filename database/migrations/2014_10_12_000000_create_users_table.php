<?php

use App\Models\Restaurant;
use App\Models\Table;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();

            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');
            $table->rememberToken();

            // roles
            $table->boolean('is_manager');
            $table->boolean('is_employee');

            // for cutomer and employee
            $table->foreignIdFor(Restaurant::class)->nullable();
            
            // for customer
            $table->foreignIdFor(Table::class)->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('users');
    }
};
