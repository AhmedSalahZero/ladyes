<?php

namespace App\Console\Commands;

use App\Enum\TravelStatus;
use App\Jobs\SendCurrentStatusMessageToEmergencyContractsJob;
use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use Illuminate\Console\Command;

class SendTravelStatusUpdateToEmergencyContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:late';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Late Travel Infos For Emergency Contact';

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
       Travel::where('status',TravelStatus::ON_THE_WAY)->where('expected_arrival_date','<',now()->addMinutes(5))->get()->each(function(Travel $travel){
			dispatch(new SendCurrentStatusMessageToEmergencyContractsJob($travel));
	   });

    }
	
}
