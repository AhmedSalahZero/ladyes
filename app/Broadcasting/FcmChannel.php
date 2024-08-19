<?php

namespace App\Broadcasting;

use App\Models\Admin;
use App\Models\Notification;
use GuzzleHttp\Client;

class FcmChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\Admin  $user
     * @return array|bool
     */
    public function join(Admin $user)
    {
        //
    }
	
	public function send($notifiable,  $notification)
{
    $message = $notification->toFcm($notifiable);

    $client = new Client();
    $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                  'headers' => [
                      'Authorization' => 'key='.env('FIREBASE_API_KEY'),
                      'Content-Type' => 'application/json',
                  ],
                  'json' => $message,
                ]);


    return $response;
}

}
