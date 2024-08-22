<?php

namespace App\Jobs;

use App\Models\Driver;
use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class sendRequestToAvailableDriversJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  public $timeout = 150; // Timeout seconds
    /**
     * Create a new job instance.
     *
     * @return void
     */
	protected Driver $availableDriver ;
	protected Travel $travel  ;
    public function __construct(Driver $availableDriver,Travel $travel)
    {
		$this->availableDriver = $availableDriver ;
		$this->travel = $travel ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		
        $this->availableDriver->sendNewTravelIsAvailable($this->travel);
    }
}
