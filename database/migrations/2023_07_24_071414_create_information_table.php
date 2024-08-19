<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
			/**
			 * * القيم المتاحة لانواع السكاشن
			 * InformationSection::class
			 */
			$table->string('section_name')->comment('زي مثلا قسم تسجيل الخروج')->nullable();
            
			$table->string('name_en')->nullable();
			$table->string('name_ar')->nullable();
			$table->text('description_en')->nullable();
			$table->text('description_ar')->nullable();
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
        Schema::dropIfExists('information');
    }
}
