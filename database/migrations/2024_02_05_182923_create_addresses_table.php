<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('client_id');
			$table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
			$table->string('name_en')->comment('نوع العنوان وليكن مثلا المنزل');
			$table->string('name_ar')->comment('نوع العنوان وليكن مثلا المنزل');
			$table->string('latitude');
			$table->string('longitude');
			$table->text('description_en')->comment('نوع العنوان وليكن مثلا المنزل');
			$table->text('description_ar')->comment('نوع العنوان وليكن مثلا المنزل');
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
        Schema::dropIfExists('addresses');
    }
}
