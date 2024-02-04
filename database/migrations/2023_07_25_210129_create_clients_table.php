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
			// $table->unsignedBigInteger('city_id')->nullable();
			// $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->string('email')->nullable();	
            $table->string('phone')->unique();
            $table->boolean('is_verified')->default(0);
            $table->boolean('can_pay_by_cash')->comment('يمكنة الدفع عن طريق الكاش')->default(1);
            // $table->boolean('can_receive_orders')->comment('استقبال الطلبات')->default(1);
            $table->string('verification_code')->nullable()->default(null);
            $table->string('birth_date')->nullable();
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
