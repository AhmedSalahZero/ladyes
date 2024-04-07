<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('travel_id')->nullable();
			$table->foreign('travel_id')->references('id')->on('travels')->nullOnDelete();
			$table->string('model_type')->comment('الشخص المطبق عليه الغرامة سواء كان سائق او عميل');
			$table->unsignedBigInteger('model_id');
			$table->decimal('amount',14,2)->comment('مقدار الغرامة المطبقة');
			$table->string('note_en')->comment('وليكن مثلا سبب الغرامة');
			$table->string('note_ar')->comment('وليكن مثلا سبب الغرامة');
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
        Schema::dropIfExists('bonuses');
    }
}
