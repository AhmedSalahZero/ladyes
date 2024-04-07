<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancellationFeesToCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->decimal('taxes_percentage',5,2)->default(14)->comment('الضريبة علي اجمالي الفاتورة عند دفع العميل')->after('id');
            $table->decimal('cash_fees',5,2)->default(1)->comment('رسوم الدفع بالكاش يعني لو العميل اختار انه يدفع كاش وقتها بيدفع مبلغ زيادة وليكن مثلا واحد ريال')->after('id');
            $table->decimal('cancellation_fees_for_client',14,2)->default(0)->comment('الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة')->after('id');
            $table->decimal('cancellation_fees_for_driver',14,2)->default(0)->comment('الرسوم اللي هيتم تطبيقها علي السائق في حالة قام بالغاء الرحلة')->after('id');
            $table->decimal('first_travel_bonus',14,2)->default(0)->comment('هو مبلغ يتم اضافتة في محفظة العميل تلقائيا بعد انتهاء اول رحلة بنجاح')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            //
        });
    }
}
