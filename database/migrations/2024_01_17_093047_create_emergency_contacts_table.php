<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->unsignedBigInteger('country_id')->nullable();
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
		
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
        Schema::dropIfExists('driver_invitation');
    }
}
