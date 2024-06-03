<?php

namespace App\Console\Commands;

use App\Enum\TravelStatus;
use App\Jobs\SendTravelNotificationsToClientsJob;
use App\Models\Travel;
use Illuminate\Console\Command;

/**
 * * كل خمس دقايق هنبعتلة الاشعارات الاتيه ( العميل)
** اذا احتجت لشئ اضغط علي زرار  الطوارئ
** اشعار باقي علي وصل رحتلك عشر دقايق مثلا 
** تكلفه الرحلة حتى الان 
 */
class SendNotificationsUpdatesToClients extends Command
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
       Travel::where('status',TravelStatus::ON_THE_WAY)->get()->each(function(Travel $travel){
			dispatch(new SendTravelNotificationsToClientsJob($travel));
	   });

    }
	
}
