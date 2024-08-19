<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_invitation', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('sender_id');
			$table->foreign('sender_id')->references('id')->on('drivers')->cascadeOnDelete();
			$table->unsignedBigInteger('receiver_id');
			$table->foreign('receiver_id')->references('id')->on('drivers')->cascadeOnDelete();
			$table->string('code');
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
