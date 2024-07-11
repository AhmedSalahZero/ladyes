<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Helpers\HDate;
use App\Http\Resources\TravelResource;
use App\Jobs\SendCurrentStatusMessageToEmergencyContractsJob;
use App\Models\CarSize;
use App\Models\Client;
use App\Models\DeviceToken;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\Transaction;

use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Services\DistanceMatrix\pointLocation;
use App\Services\Firebase;
use App\Services\FirebaseService;
use Firebase as GlobalFirebase;
use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase as FacadesFirebase;
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
		$travel = new Travel();
		dd($travel->save());
		// نبحث عن السواقين المتاحين 
		// نعطي كل سواق دقيقه مثلا وان لم يستجب ندي للسواق اللي بعدة
		// بمجرد ما يوافق واحد نبعت استجابة ترو والاي دي بتاع السواق
		// في حالة انتهى الوقت ولم يحدث شئ نبعت فولس
		$longitude = '-8.000545';
		$latitude = '-19.361057';
		
		$availableDrivers = Driver::getAvailableForSpecificLocationsAndCarSize($latitude,$longitude,2);
		foreach($availableDrivers as $availableDriver){
			// send notification 
			 
			// then sleep (wait) for for 30 seconds for example
			sleep(30);
			
			// if()
			
		}
		
    }
   
}
