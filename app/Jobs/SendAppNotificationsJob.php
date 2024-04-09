<?php

namespace App\Jobs;

use App\Interfaces\IHaveAppNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAppNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	protected IHaveAppNotification $clientOrDriver ;
	protected string $titleEn;
	protected string $titleAr;
	protected string $messageEn;
	protected string $messageAr;
	protected string $type;
	protected ?int $modelId;
    public function __construct(IHaveAppNotification $clientOrDriver ,string $titleEn,string $titleAr,string $messageEn,string $messageAr,string $type,?int $modelId)
    {
		$this->clientOrDriver = $clientOrDriver ;
		$this->titleEn = $titleEn;
		$this->titleAr = $titleAr;
		$this->messageEn = $messageEn;
		$this->messageAr = $messageAr;
		$this->type = $type;
		$this->modelId = $modelId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->clientOrDriver->sendAppNotification($this->titleEn,$this->titleAr,$this->messageEn,$this->messageAr,$this->type,$this->modelId);
    }
}
