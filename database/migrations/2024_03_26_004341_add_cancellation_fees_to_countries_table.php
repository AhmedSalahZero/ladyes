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
            // $table->decimal('first_travel_bonus',14,2)->default(0)->comment('هو مبلغ يتم اضافتة في محفظة العميل تلقائيا بعد انتهاء اول رحلة بنجاح')->after('id');
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
