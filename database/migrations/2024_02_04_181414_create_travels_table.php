<?php

use App\Enum\TravelStatus;
use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('client_id')->nullable();
			$table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
			$table->unsignedBigInteger('driver_id')->nullable();
			$table->foreign('driver_id')->references('id')->on('drivers')->nullOnDelete();
			// $table->unsignedBigInteger('country_id')->nullable();
			// $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
			$table->unsignedBigInteger('city_id')->nullable();
			$table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
			
			$table->unsignedBigInteger('coupon_id')->comment('دا الكوبون اللي تم تطبيقه علي الرحلة الحالية')->nullable();
			$table->unsignedBigInteger('gift_coupon_id')->comment('دا الكوبون اللي بيتم انشائة تلقائي بحيث لما الرحلة تنتهي بيظهر للعميل كهديه بحيث يستخدمة في الرحلات القادمة')->nullable();
			$table->enum('status',array_keys(TravelStatus::all()))->default(TravelStatus::NOT_STARTED_YET)->comment('حالة الرحلة هل لم تبدا بعد اما في الطريق ام انتهت ام تم الغائها الخ');
			$table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete();
			$table->foreign('gift_coupon_id')->references('id')->on('coupons')->nullOnDelete();
			// $table->decimal('coupon_amount',14,2)->default(0)->comment('هنضيف الكوبون هنا لان ممكن السعر يتغير وبالتالي مقدرش اجيبه عن طريق الريليشن');
			$table->string('from_longitude');
			$table->string('to_longitude');
			$table->string('from_latitude');
			$table->string('to_latitude');
			$table->string('address')->comment('العنوان');
			$table->boolean('is_secure')->default(0)->comment('هل نوع الرحلة رحلة امنة ؟ لو امنة بننشئ كود بحيث السواق يقوله للعميل علشان يتاكد من هويته');
			$table->string('secure_code')->nullable()->default(null)->comment('is_secure رمز الرحلة الامنة ويتم انشائه تلقائي في حالة لو تم تفعيل خيار الرحلة الامنه');
			$table->dateTime('started_at')->nullable()->comment('الوقت اللي الرحلة بدات فيه .. يعني اول ما العميل ركب السيارة');
			$table->dateTime('ended_at')->nullable()->comment('الوقت اللي الرحلة انتهت فيه ..  يعني اول ما العميل نزل  من السيارة');
			$table->decimal('no_km',14,2)->comment('عدد الكيلوا مترات المقطوعه خلال كامل الرحلة')->nullable();
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
        Schema::dropIfExists('travels');
    }
}
