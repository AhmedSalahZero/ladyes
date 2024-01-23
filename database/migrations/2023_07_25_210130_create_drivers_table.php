<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
			
            // $table->string('country_code')->nullable();
			
			$table->unsignedBigInteger('country_id')->nullable();
			
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
			
			$table->unsignedBigInteger('city_id')->nullable();
			$table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
			
			$table->unsignedBigInteger('area_id')->nullable();
			$table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
			
			
            $table->string('email')->nullable();	
            $table->string('phone')->unique();
	
            $table->boolean('is_verified')->default(0);
			
            $table->boolean('is_listing_to_orders_now')->comment('هو الان في حاله انتظار الطلبات')->default(0);
            $table->boolean('can_receive_orders')->comment('استقبال الطلبات')->default(1);
            $table->string('verification_code')->nullable()->default(null);
            $table->string('birth_date')->nullable();
            $table->string('id_number')->comment('رقم الهوية / الاقامة')->nullable();
            $table->float('deduction_percentage')->nullable()->comment('نسبة الاستقطاع ضعها سالب واحد لاستخدام القيمه الافتراضيه في الاعدادت')->default(-1);
			
			$table->unsignedBigInteger('size_id')->nullable();
			$table->foreign('size_id')->references('id')->on('car_sizes')->nullOnDelete();
			
			$table->unsignedBigInteger('make_id')->nullable()->comment('ماركة السيارة');
			$table->foreign('make_id')->references('id')->on('car_makes')->nullOnDelete();
			
			$table->unsignedBigInteger('model_id')->nullable()->comment('موديل السيارة');
			$table->foreign('model_id')->references('id')->on('car_models')->nullOnDelete();
			
			$table->integer('manufacturing_year')->comment('سنه الصنع')->nullable();
			$table->string('plate_letters')->comment('حروف اللوحة')->nullable();
			$table->string('plate_numbers')->comment('رقم اللوحة')->nullable();
			$table->string('car_color')->comment('لون السيارة')->nullable();
			$table->string('car_max_capacity')->comment('السعة القصوى للمركبة')->nullable();
			$table->string('car_id_number')->comment('الرقم التسلسلي للمركبة')->nullable();
			$table->boolean('has_traffic_tickets')->comment('هل لديك مخالفات مروريه')->nullable()->default(0);
			// $table->string('front_image')->comment('صورة السيارة من الخلف')->nullable();
			// $table->string('back_image')->comment('صورة السيارة من الامام')->nullable();
			
			
            // $table->string('image')->comment('صورة شخصية')->nullable();
            // $table->string('id_number_image')->comment('صورة الهوية الوطنيه او الاقامة')->nullable();
            // $table->string('insurance_image')->comment('صورة التامين')->nullable();
            // $table->string('driver_license_image')->comment('صورة رخصة القيادة')->nullable();
			
            $table->string('invitation_code')->unique()->nullable();
            // $table->integer('car_type')->default(1);
            // $table->integer('model_id')->default(1);
            // $table->string('car_number')->nullable();
            // $table->string('car_model')->nullable();
            // $table->string('car_color')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
