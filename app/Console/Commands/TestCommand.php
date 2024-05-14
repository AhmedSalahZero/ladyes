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
use App\Services\DistanceMatrix\pointLocation;
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
		dd(
			$this->get_api(30.09600521354317, 31.247130250361646),
			$this->get_api(30.016961502165294, 31.22687420867544)
	);
		
			// $test = new pointLocation();
			// $polygon  = ["30.00916 30.46525 33.11829 29.60451"];
			// $point = ["21.457025157211604 39.79846234659879"];
			// $test->pointInPolygon($point,$polygon);
		
    }
   
}
