<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('travel_id')->nullable();
			$table->foreign('travel_id')->references('id')->on('travels')->nullOnDelete();
			// $table->string('model_type')->comment('الشخص المطبق عليه الغرامة سواء كان سائق او عميل');
			// $table->unsignedBigInteger('model_id');
			
			$table->unsignedBigInteger('client_id');
			$table->foreign('client_id')->references('id')->on('clients');
			
			$table->decimal('amount',14,4)->comment('مقدار الغرامة المطبقة');
			// $table->boolean('is_paid')->default(false)->comment('تم دفعها ولا لسه');
			$table->string('note_en')->comment('وليكن مثلا سبب اعادة تحويل الاموال');
			$table->string('note_ar')->comment('وليكن مثلا سبب اعادة تحويل االموال');
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
        Schema::dropIfExists('fines');
    }
}
