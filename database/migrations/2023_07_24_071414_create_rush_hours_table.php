<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRushHoursTable extends Migration
{

    public function up()
    {
        Schema::create('rush_hours', function (Blueprint $table) {
            $table->id();
			$table->time('start_time')->nullable();
			$table->string('end_time')->nullable();
			// $table->string('price')->comment('الاجرة الاساسية في وقت الذروة');
			$table->string('km_price')->comment('السعر لكل كيلو متر');
			$table->string('minute_price')->comment('السعر لكل دقيقة');
			$table->string('operating_fees')->comment('رسوم التشغيل');
			$table->string('percentage')->comment('نسبة الذروة (نسبة كمعلومة لا تتجاوز 5/5 )');
			$table->decimal('cancellation_fees_for_client',14,2)->default(0)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
			$table->decimal('late_fees_for_client',14,2)->default(0)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالتاخر علي الرحلة وليكن مثلا السائق وصول وفضل منتظرة كثير اكثر من خمس دقايق');
            $table->decimal('cash_fees',14,2)->default(1)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
            $table->decimal('first_travel_bonus',14,2)->default(1)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
			$table->unsignedBigInteger('city_id')->nullable();
			$table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
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
        Schema::dropIfExists('rush_hours');
    }
}
