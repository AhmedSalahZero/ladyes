<?php
namespace App\Services\Whatsapp;

use App\Settings\SiteSetting;

class WhatsappService
{
	protected string $appKey ;
	protected string $authKey ;
	public function __construct()
	{
		$siteSetting = App(SiteSetting::class);
		$this->appKey = env('WHATSAPP_APP_KEY',$siteSetting->WHATSAPP_APP_KEY);
		$this->authKey = env('WHATSAPP_AUTH_KEY',$siteSetting->WHATSAPP_AUTH_KEY);
	}
	/**
	 * @param message message:'test message',
	 * @param phoneNumber +201025894984 [use phoneNumberService To Format The Number], 
	 * */ 
    public function sendMessage(string $message, string $phoneNumber):array 
    {
		
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://whats.mnjz.sa/api/create-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
        'appkey' => $this->appKey,
        'authkey' => $this->authKey,
        'to' => $phoneNumber,
        'message' => $message,
        'sandbox' => 'false'
        ),
        ));
		$result = curl_exec($curl);
        curl_close($curl);
		
		$result = json_decode($result,true);
		
		if(isset($result['data']) && isset($result['message_status'])){
			return [
				'status'=>true ,
				'message'=>$result['message_status'],
				'from'=>$result['data']['from'],
				'to'=>$result['data']['to'],
				'status_code'=>$result['data']['status_code'],
			];
		}
		if(isset($result['error'])){
			return [
				'status'=>false ,
				'message'=>$result['error']
			] ;
		}
		
		return [
			'status'=>false ,
			'message'=>__('Unknown Error')
		];

    }
}
