<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelEmergencyContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_emergency_contact', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('emergency_contact_id');
			$table->foreign('emergency_contact_id')->references('id')->on('emergency_contacts')->cascadeOnDelete();
			$table->unsignedBigInteger('model_id');
			$table->string('model_type');
			$table->boolean('can_receive_travel_info')->nullable();
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
        Schema::dropIfExists('model_emergency_contact');
    }
}
