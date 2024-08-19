<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * * من هنا بنعرف كل يوزر مربوط بانهي شروط رحلة وليكن مثلا السواق الفولاني محدد اول اربع شروط
 * * او العميل الفلاني محدد اخر اتنين وهكذا
 */
class CreateUserTravelConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	
    public function up()
    {
        Schema::create('user_travel_conditions', function (Blueprint $table) {
            $table->id();
			$table->string('model_type')->comment('Driver Or Client');
			$table->unsignedBigInteger('model_id')->comment('Driver Id Or Client Id');
			$table->unsignedBigInteger('travel_condition_id');
			$table->foreign('travel_condition_id')->references('id')->on('travel_conditions')->cascadeOnDelete();
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
        Schema::dropIfExists('user_travel_conditions');
    }
}
