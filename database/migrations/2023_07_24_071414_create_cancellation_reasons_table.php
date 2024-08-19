<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\CancellationReasonPhases;

class CreateCancellationReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_reasons', function (Blueprint $table) {
            $table->id();
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			$table->string('model_type')->comment('Driver Or Client For Example')->nullable();
			$table->enum('phase',array_keys(CancellationReasonPhases::all()))->nullable()->comment('عندنا ثلاث انواع لرسائل الالغاء او ثلاث اماكن يقدر العميل يلغي منهم قبل وصول السائق او بعد وصوله');
            $table->string('is_active')->default(0)->boolean();
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
        Schema::dropIfExists('cancellation_reasons');
    }
}
