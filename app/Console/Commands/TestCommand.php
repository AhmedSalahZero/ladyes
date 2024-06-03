<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Helpers\HDate;
use App\Http\Resources\TravelResource;
use App\Jobs\SendCurrentStatusMessageToEmergencyContractsJob;
use App\Models\CarSize;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Notification;

use App\Models\Transaction;
use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Services\DistanceMatrix\pointLocation;
use Illuminate\Console\Command;
use Postmark\PostmarkClient;

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
		// $travel = Travel::where('ended_at',null)->first();
		$travel = Travel::find(2);
		$x = $travel->SendTravelNotificationsToClients();
		dd($x);
		
		// $client = Client::first();
		// $client->sendAppNotification('title en', 'title ar','message en','message ar','type here');
			
    }
   
}
