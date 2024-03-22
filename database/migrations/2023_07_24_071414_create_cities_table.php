<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			// $table->string('price')->comment('الاجرة الاساسية للمدينة (خارج اوقات الذروة)');
			$table->string('km_price')->comment('السعر لكل كيلو متر');
			$table->string('minute_price')->comment('السعر لكل دقيقة');
			$table->string('operating_fees')->comment('رسوم التشغيل');
			$table->unsignedBigInteger('country_id')->nullable();
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
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
        Schema::dropIfExists('cities');
    }
}
