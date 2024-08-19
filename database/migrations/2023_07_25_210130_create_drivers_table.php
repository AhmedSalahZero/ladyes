<?php

use App\Settings\SiteSetting;
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
			$table->decimal('current_wallet_balance',14,4)->comment('هو عباره عن اجمالي الفلوس اللي في محفظته حاليا وبنحسبها من جدول ال transactions')->default(0);
			$table->unsignedBigInteger('country_id')->nullable();
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
			$table->unsignedBigInteger('city_id')->nullable();
			$table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->string('email')->nullable();	
            $table->string('phone')->unique();
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_listing_to_orders_now')->comment('هو الان في حاله انتظار الطلبات')->default(0);
            $table->boolean('can_receive_orders')->comment('استقبال الطلبات')->default(1);
            // $table->string('verification_code')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('id_number')->comment('رقم الهوية / الاقامة')->nullable();
			$table->string('deduction_type')->nullable();
            $table->decimal('deduction_amount',14,4)->nullable()->comment('قيمة او نسبة الاستقطاع علي حسب النوع');
            $table->integer('driving_range')->nullable()->comment('نطاق السائق (نطاق الطلابات) اللي يقدر يستلم منه طلبات وليكن مثلا 15 كيلو من مكانه')->default(20);
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
            $table->string('invitation_code')->comment('رمز الدعوة الخاص بالسائق اللي يمكن ارسالة الي سائقين اخرين')->unique()->nullable();
			$table->string('device_id')->comment('علشان الاشعارات من الفرونت')->nullable();
			$table->string('device_type')->comment('نوع الجهاز وليكن مثلا اندرويد')->nullable();
			
			/**
			 * * هو عباره عن اخر لوكيشن للسائق واللوكيشن دا بيتحدث كل مره السائق بيحدد نفسه انه متصل علشان يستقبل طلابات
			 */
			$table->point('location')->nullable();
			$table->boolean('has_excellent_medal')->default(0)->comment('لدية وسام سائق مميز .. لو عدى شهر كامل وكان تقيمة اربعه او اكثر');
			$table->boolean('has_one_year_usage_medal')->comment('وسام مستخدم سنة واحدة .. لو مر علي استخدامة الابلكيشن اكثر من سنة')->default(0);
			$table->boolean('has_completed_50_travel_medal')->comment('وسام اكمال اكثر من خمسين رحلة')->default(0);
			$table->boolean('has_rush_hour_medal')->default(0)->comment('وسام العمل وقت الذروة لفترة كبيرة .. لو اشتغل وقت الذروة لشهر كامل');
			
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
