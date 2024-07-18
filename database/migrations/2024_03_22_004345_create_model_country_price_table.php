<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelCountryPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_country_price', function (Blueprint $table) {
            $table->id();
			$table->string('model_type')->comment('نوع الموديل وليكن مثلا CarSize');
			$table->unsignedBigInteger('model_id');
			$table->unsignedBigInteger('country_id');
			$table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
			$table->decimal('price',14,4)->default(0)->comment('هو عبارة عن السعر');
			$table->decimal('sum_price')->default(10)->comment('دا في السعر اللي بنحسبة من الي .. بنضيف الرقم دا الي المن علشان نجيب ال الى');
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
        Schema::dropIfExists('model_country_price');
    }
}
