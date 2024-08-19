<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

	/**
	 ** هنا هنحتفظ بسجل الاوقات اللي اشتغل فيها في اوقات الذروة 
	 * * علشان لو اشتغل شهر كامل في وقت الذروة يوميا بياخد وسام
	 */
	
class CreateDriverRushHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_rush_hours', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('driver_id');
			$table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
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
        Schema::dropIfExists('driver_rush_hours');
    }
}
