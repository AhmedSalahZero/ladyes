<?php

use App\Enum\DiscountType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			$table->date('start_date')->comment('تاريخ بداية العرض');
			$table->date('end_date')->comment('تاريخ نهاية العرض');
			$table->enum('discount_type',array_keys(DiscountType::all()))->comment('نوع الخصم هيكون نسبة ولا قيمة ثابتة');
			$table->string('amount')->comment('قد تكون نسبة او قيمة ثابتة علي حسب النوع ');
            // $table->string('is_active')->default(0)->boolean();
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
        Schema::dropIfExists('promotions');
    }
}
