<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
			$table->string('model_type')->comment('الشخص اللي الفلوس دي موجهه لية في السحب سواء كان سائق او عميل');
			$table->unsignedBigInteger('model_id');
			$table->string('payment_method')->comment('الطريقه التي تمت بها عمليه السحب وليكن مثلا كاش')->nullable();
			$table->decimal('amount',14,2)->comment('مقدار ما تم سحبة');
			$table->string('note_en')->comment('وليكن مثلا سبب السحب');
			$table->string('note_ar')->comment('وليكن مثلا سبب سبب السحب');
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
        Schema::dropIfExists('withdrawals');
    }
}
