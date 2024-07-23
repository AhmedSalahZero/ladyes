<?php

use App\Enum\DiscountType;
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
			$table->decimal('price',14,4)->default(0)->comment('سعر الرحلة الاساسي بدون رسوم التشغيل او الخصم');
			$table->string('promotion_type',14,4)->default(DiscountType::FIXED)->comment('هنا عباره عن نوع العرض الترويجي لو موجود');
			$table->decimal('promotion_amount',14,4)->default(0)->comment('هنا عباره عن نسبة او قيمة العرض الترويجي لو موجود');
			$table->decimal('coupon_amount',14,4)->default(0)->comment('هنضيف الكوبون هنا لان ممكن السعر يتغير وبالتالي مقدرش اجيبه عن طريق الريليشن');
			$table->decimal('operational_fees',14,4)->default(0)->comment('عباره عن رسوم التشغيل الخاصة بالمدينة وبتختلف بناء علي هل هي وقت ذروة اثناء بداية الرحلة ولا لا');
			$table->decimal('cash_fees',5,2)->comment('لو اليوزر اختار انه يدفع كاش وقتها بيدفع وليكن مثلا واحد ريال زيادة')->default(0);
			$table->decimal('tax_amount',14,4)->comment('ضريبة وليكن مثلا ضريبة القيمة المضافة')->default(0);
			$table->decimal('total_fines',14,4)
			->default(0)
			->comment('اجمالي الغرمات السابقة اللي هيدفعها لما يجي يسدد ثمن الرحلة لان مع كل تسديد ثمن رحلة لازم يسدد كل الغرمات السابقة');
			$table->decimal('application_share')->default(0)->comment('هي النسبة اللي التطبيق هياخدها');
			$table->decimal('driver_share')->default(0)->comment('هي النسبة اللي السواق هياخدها');
			$table->decimal('car_size_price')->default(0)->comment('السعر الخاص بالدولة لنوع السيارة المستخدمة من قبل السائق');
			
			$table->decimal('total_price',14,4)->default(0)->comment('اجمالي السعر اللي اليوزر هيدفعه بعد الخصومات الخ');
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
