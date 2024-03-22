<?php

use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
			$table->enum('status',array_keys(PaymentStatus::all()))->default(PaymentStatus::PENDING);
			$table->enum('type',array_keys(PaymentType::all()))->default(PaymentType::CASH);
			$table->decimal('price',14,2)->default(0)->comment('سعر الرحلة الاساسي بدون رسوم التشغيل او الخصم');
			$table->decimal('coupon_amount',14,2)->default(0)->comment('هنضيف الكوبون هنا لان ممكن السعر يتغير وبالتالي مقدرش اجيبه عن طريق الريليشن');
			$table->decimal('operational_fees',14,2)->default(0)->comment('عباره عن رسوم التشغيل الخاصة بالمدينة وبتختلف بناء علي هل هي وقت ذروة اثناء بداية الرحلة ولا لا');
			
			
			$table->decimal('total_price',14,2)->default(0)->comment('اجمالي السعر اللي اليوزر هيدفعه بعد الخصومات الخ');
			$table->unsignedBigInteger('travel_id');
			$table->string('currency_name')->nullable();
			$table->foreign('travel_id')->references('id')->on('travels')->cascadeOnDelete();
			$table->unsignedBigInteger('model_id')->nullable()->comment('هو الشخص اللي هيدفع وليكن مثلا العميل');
			$table->string('model_type')->comment('نوع مودل الشخص اللي عليه المدفوعه دي وليكن مثلا السائق او العميل');
			// $table->unsignedBigInteger('transaction_id')->nullable();
			// $table->foreign('transaction_id')->references('id')->on('transactions')->nullOnDelete();
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
        Schema::dropIfExists('payments');
    }
}
