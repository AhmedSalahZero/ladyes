<?php 
namespace App\Services\SMS;

class SmsService  {
	
	public function send(string $phone , string $countryCode , string $message)
	{
		$twilioService = new TwilioService();
		return $twilioService->sendSmsMessage($phone,$countryCode,$message);
	}
}
