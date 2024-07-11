<?php
namespace App\Channels;
use App\Notifications\Admins\ClientNotification;
use App\Notifications\Admins\DriverNotification;
use App\Services\FirebaseService;
use Exception;
use Illuminate\Notifications\Notification;

class FirebaseChannel 
{
	
	public function send ($notifiable,  $notification) {
		/**
		 * @var DriverNotification|ClientNotification $notification
		 */
		$service = new FirebaseService ;
		if(!method_exists($notification , 'toFirebase')){
			throw new Exception('toFirebase method not found !');
		}
		foreach($notifiable->getDeviceTokens() as $deviceToken){
			$service->sendMessage($deviceToken , $notification->toFirebase($notifiable));
		}
    }
}
