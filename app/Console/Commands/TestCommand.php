<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Models\CarSize;
use App\Models\Client;
use App\Models\Notification;
use App\Models\Transaction;

use App\Models\Travel;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$first = Notification::where('data->title_en','Deposit')->first();
		$first = Notification::where('data->title_en','Deposit')->where('data->model_id',1)->first();
		dd($first);
		
		dd('good');
		Transaction::create([
			'amount'=>500000,
			'type'=>TransactionType::DEPOSIT,
			'model_id'=>1 ,
			'model_type'=>'Client',
			'note_en'=>'en',
			'note_ar'=>'ar',
		]);
	
		dd('good');
		$client = Client::first();
		$verificationCode = $client->getVerificationCode();
		dd($verificationCode);
		$carSize = CarSize::first();
		$travel = Travel::first();
		/**
		 * @var Travel $travel ;
		 */
		$travel->calculateClientActualPriceWithoutDiscount();
    }
   
}
