<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
			$table->unsignedBigInteger('country_id')->nullable();
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
			$table->decimal('current_wallet_balance',14,4)->comment('هو عباره عن اجمالي الفلوس اللي في محفظته حاليا وبنحسبها من جدول ال transactions')->default(0);
            $table->string('email')->nullable();	
            $table->string('phone')->unique();
            $table->boolean('is_verified')->default(0);
            $table->boolean('can_pay_by_cash')->comment('يمكنة الدفع عن طريق الكاش')->default(1);
            // $table->string('verification_code')->nullable()->default(null);
            $table->string('birth_date')->nullable();
			$table->point('location')->nullable();
			$table->boolean('has_secure_travel')->default(1)->comment('مفعل نظام الرحلة الامنة');
			// $table->boolean('can_receive_notifications')->default(1)->comment('يمكنة استلام اشعارات وعروض');
			$table->string('device_id')->comment('علشان الاشعارات من الفرونت')->nullable();
			$table->string('device_type')->comment('نوع الجهاز وليكن مثلا اندرويد')->nullable();
			$table->timestamp('banned_at')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
