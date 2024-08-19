<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationReasonClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/**
		 *  * الجدول دا هنستخدمة في حالة فقط لو اليوزر عمل الغاء قبل تسجيلل رحلة وبالتالي احنا معندناش رحلة علشان نلغيها
		 * * فا هنقول ان اليوزر الفولاني لغى بسبب كذا 
		 */
        Schema::create('cancellation_reason_client', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('client_id');
			$table->foreign('client_id')->references('id')->on('clients');
			$table->unsignedBigInteger('cancellation_reason_id');
			$table->foreign('cancellation_reason_id')->references('id')->on('cancellation_reasons');
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
        Schema::dropIfExists('cancellation_reason_client');
    }
}
