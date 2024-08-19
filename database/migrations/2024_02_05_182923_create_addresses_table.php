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
			$table->string('category')->comment('فئة العنوان وليكن مثلا المنزل');
			$table->string('latitude');
			$table->string('longitude');
			$table->text('description')->comment('وصف العنوان وليكن مثلا المنزل');
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
