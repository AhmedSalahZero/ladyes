<?php

use App\Enum\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 14, 2)->default(0);
            $table->enum('type', array_keys(TransactionType::all()))->default(TransactionType::PAYMENT);
            $table->unsignedBigInteger('type_id')->nullable()->comment('علي سبيل المثال ال payment_id');
            $table->unsignedBigInteger('model_id')->nullable()->comment('علي سبيل المثال ال client_id , or driver_id');
            $table->string('model_type')->nullable()->comment('Client Or Driver Model');
            $table->string('note_en')->comment('ملحوظة .. وليكن مثلا تم ايداع المبلغ الي محفظتك مثلا')->nullable();
            $table->string('note_ar')->comment('ملحوظة .. وليكن مثلا تم ايداع المبلغ الي محفظتك مثلا')->nullable();
            $table->timestamps();
        });
        DB::unprepared('DROP PROCEDURE if exists `update_client_wallet_net_balance`');
        DB::unprepared('
			create PROCEDURE update_client_wallet_net_balance( id int )
			BEGIN
				declare _total_balance decimal(14,0) default 0;
				select sum(amount) into _total_balance from transactions where model_type = "Client" and model_id = 	id	;
							update clients set current_wallet_balance = ifnull(_total_balance,0) where id = id;
			END; 
        ');

        DB::unprepared('
			CREATE TRIGGER update_client_wallet_after_insert AFTER INSERT ON `transactions` FOR EACH ROW
				BEGIN
					call update_client_wallet_net_balance(new.model_id);
				END ;
			');

        DB::unprepared('
			CREATE TRIGGER update_client_wallet_after_update AFTER UPDATE ON `transactions` FOR EACH ROW
				BEGIN
					call update_client_wallet_net_balance(new.model_id);
				END ;
			');

        DB::unprepared('
			CREATE TRIGGER update_client_wallet_after_delete AFTER delete ON `transactions` FOR EACH ROW
				BEGIN
					call update_client_wallet_net_balance(old.model_id);
				END ;
			');
			
			
			
			
			
			
			
			
			DB::unprepared('DROP PROCEDURE if exists `update_driver_wallet_net_balance`');
        DB::unprepared('
			create PROCEDURE update_driver_wallet_net_balance( id int )
			BEGIN
				declare _total_balance decimal(14,0) default 0;
				select sum(amount) into _total_balance from transactions where model_type = "Driver" and model_id = 	id	;
							update drivers set current_wallet_balance = ifnull(_total_balance,0);
			END; 
        ');

        DB::unprepared('
			CREATE TRIGGER update_driver_wallet_after_insert AFTER INSERT ON `transactions` FOR EACH ROW
				BEGIN
					call update_driver_wallet_net_balance(new.model_id);
				END ;
			');

        DB::unprepared('
			CREATE TRIGGER update_driver_wallet_after_update AFTER UPDATE ON `transactions` FOR EACH ROW
				BEGIN
					call update_driver_wallet_net_balance(new.model_id);
				END ;
			');

        DB::unprepared('
			CREATE TRIGGER update_driver_wallet_after_delete AFTER delete ON `transactions` FOR EACH ROW
				BEGIN
					call update_driver_wallet_net_balance(old.model_id);
				END ;
			');
			
			
			
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
