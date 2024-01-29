<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_conditions', function (Blueprint $table) {
            $table->id();
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			$table->string('model_type')->comment('Driver Or Client For Example')->nullable();
            $table->string('is_active')->default(0)->boolean();
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
        Schema::dropIfExists('travel_conditions');
    }
}
