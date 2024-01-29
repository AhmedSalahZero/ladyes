<?php

namespace App\Services\SMS ;

use App\Interfaces\ISmsService;
use App\Services\PhoneNumberService;
use Twilio\Rest\Client;

class TwilioService implements ISmsService
{
    protected $account_sid ;
    protected $auth_token ;
    protected $twilio_number ;

    public function __construct()
    {
        $this->account_sid = getenv('TWILIO_SID');
        $this->auth_token = getenv('TWILIO_AUTH_TOKEN');
        $this->twilio_number = getenv('TWILIO_NUMBER');

    }

    public function sendSmsMessage(string $phone, string $countryCode, string $message): array 
    {
        $client = new Client($this->account_sid, $this->auth_token);
        $phoneService = new PhoneNumberService();
        $formattedNumber = $phoneService->formatNumber($phone, $countryCode);
        try{
			$client->messages->create(
				$formattedNumber,
				['from' => $this->twilio_number, 'body' => $message]
			);
			
			return [
				'status'=>true  ,
				'message'=>__('New Sms Message Has Been Sent To Your Phone Address')
			];
		}
		catch(\Exception $e){
			return [
				'status'=>false ,
				'message'=>$e->getMessage()
			];
		}
    }
}
