<?php

namespace App\Jobs;

use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCurrentStatusMessageToEmergencyContractsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	protected Travel $travel ;
    public function __construct(Travel $travel)
    {
        $this->travel =$travel ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->travel->sendCurrentStatusMessageToEmergencyContracts();
    }
}
