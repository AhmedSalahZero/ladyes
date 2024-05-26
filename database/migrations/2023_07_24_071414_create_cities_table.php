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
			
			$table->point('location')->comment('عباره عن اي نقطة تقع في هذا المحافظة علشان نعرف نجيبها بمعرفه اللات والانج لاي نقطة تانيه تقع في نفس المحافظة');
			// $table->polygon('area')->comment('دا عباره عن الاربع المستطيل اللي بتقع فيه المدينة ودا مهم جدا');
			// $table->string('price')->comment('الاجرة الاساسية للمدينة (خارج اوقات الذروة)');
			$table->string('km_price')->comment('السعر لكل كيلو متر');
			$table->string('minute_price')->comment('السعر لكل دقيقة');
			$table->string('operating_fees')->comment('رسوم التشغيل');
            $table->decimal('cancellation_fees_for_client',14,2)->default(0)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
            $table->decimal('cash_fees',14,2)->default(1)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
            $table->decimal('first_travel_bonus',14,2)->default(1)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة');
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
