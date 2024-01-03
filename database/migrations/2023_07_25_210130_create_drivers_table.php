<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password')->nullable();
            $table->boolean('active')->default(0);
            $table->string('id_number')->nullable();
            $table->integer('city_id')->default(0);
            $table->integer('area_id')->default(1);
            $table->integer('car_type')->default(1);
            $table->integer('model_id')->default(1);
            $table->string('car_number')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_color')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
