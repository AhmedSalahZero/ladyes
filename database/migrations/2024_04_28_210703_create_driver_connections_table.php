<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

	/**
	 * * عباره عن سجل الاتصال و الانقطاع لهذا السائق 
	 * * بمعني لما السائق بيعمل اتصال علشان يبدا يستقبل طلبات هنسجلها
	 * * ولما يعمل لوج اوت او ينهي الاتصال هنقفلها
	 */
	
class CreateDriverConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('driver_connections', function (Blueprint $table) {
        //     $table->id();
		// 	$table->dateTime('started_at')->nullable();
		// 	$table->dateTime('ended_at')->nullable();
		// 	$table->unsignedBigInteger('driver_id');
		// 	$table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_connections');
    }
}
