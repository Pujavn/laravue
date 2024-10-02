<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_city', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign key for users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Cascade on user delete

            // Foreign key for cities
            $table->foreignId('city_id')
                  ->constrained('cities')
                  ->onDelete('cascade'); // Cascade on city delete

            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_city');
    }
}
