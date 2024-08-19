<?php 
namespace App\Services;

use App\Models\DeviceToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseService
{
	public function sendMessage(string $deviceToken , array $messageBody ):void{
		// $messageBody = [
		// 	'title' => $messageTitle,
		// 	'body' => $messageBody,
		// 	'main_type'=>$mainType , // for example chat , notification
		// 	'secondary_type'=>$secondaryType  // type of chat or notification for if main type is chat this also will be chat , if main type is notification then it may be [deposit, fine , etc]
		// ];
		
		$firebase = (new Factory)
		->withServiceAccount(storage_path('app/ladyes-70ee3-8e389ab89911.json'));
		
		$messaging = $firebase->createMessaging();
		
		$message = CloudMessage::withTarget('token', $deviceToken)
		->withNotification($messageBody)
		->withData([])
		;
		
		 $messaging->send($message);
		
	}
}
