<?php

use App\Enum\DiscountType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			$table->string('code')->comment('كود الكوبون');
			$table->string('number_of_uses')->default(1)->comment('اقصى عدد من المرات يمكن استخدام هذا الكوبون')->nullable();
			$table->date('start_date')->nullable()->comment('تاريخ بداية العرض');
			$table->date('end_date')->nullable()->comment('تاريخ نهاية العرض');
			$table->string('discount_type')->default(DiscountType::FIXED)->comment('في حالة الكوبونات فهي دايما ثابتة');
			$table->decimal('discount_amount',14,4)->comment('في حالة الكوبونات فهي دايما قيمة ثابتة');
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
        Schema::dropIfExists('coupons');
    }
}
