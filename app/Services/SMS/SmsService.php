<?php 
namespace App\Services\SMS;

class SmsService  {
	
	public function send(string $phone , string $countryIso2 , string $message)
	{
		$twilioService = new TwilioService();
		return $twilioService->sendSmsMessage($phone,$countryIso2,$message);
	}
}
