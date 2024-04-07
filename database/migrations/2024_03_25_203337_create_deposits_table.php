<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
			$table->string('model_type')->comment('الشخص اللي الفلوس دي موجهه لية في الايداع سواء كان سائق او عميل');
			$table->unsignedBigInteger('model_id');
			$table->decimal('amount',14,2)->comment('مقدار ما تم ايداعة المطبقة');
			$table->string('note_en')->comment('وليكن مثلا سبب الايداع');
			$table->string('note_ar')->comment('وليكن مثلا سبب سبب الايداع');
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
        Schema::dropIfExists('deposits');
    }
}
