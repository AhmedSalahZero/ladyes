<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelTrakingsTable extends Migration
{

    public function up()
    {
        Schema::create('travel_trakings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
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
        Schema::dropIfExists('travel_trakings');
    }
}
