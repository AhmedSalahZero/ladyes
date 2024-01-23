<?php
namespace App\Services\SMS ;


use App\UserVerify;
use Twilio\Rest\Client;

class SendSmsService {
    public function __execute(string $phone ,  $user , $countryCode)
    {


             $randomConfirmationCode = rand(1000,9999);

             $phoneVerifiedRecord = UserVerify::where('user_id',$user->user_id)
                 ->where('type','phone')->exists();

             if($phoneVerifiedRecord)
             {
                 UserVerify::where('user_id',$user->user_id)
                     ->where('type','phone')
                     ->update([
                         'code'=>$randomConfirmationCode ,
                         'verified'=>false
                     ]);
                      $user->status->update([
                        'is_confirmed_mobile'=>false
                    ]);
                    
             }
             else {
                 UserVerify::create([
                     'user_id'=>$user->user_id,
                     'code'=>$randomConfirmationCode ,
                     'type'=>'phone',
                     'phoneOrEmail'=>$phone ,
                     'verified'=>false
                 ]);

                   $user->status->update([
                        'is_confirmed_mobile'=>false
                    ]);


             }
        $this->sendSmsMessage($randomConfirmationCode  , $phone , $countryCode);

    }
	public function sendSmsMessage($randomConfirmationCode , $phone , $countryCode , $message = null)
    {
        // $test_user = $this ;
        // $test_user->phone_number = '+0201025894984';
        // $test_user->To = 1025894984;
        // $test_user->to =10258984984;
//dd();
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
//dd('+'.Request('country') . Request('phone'));
			$messageBody = $message ?: "Hi , $this->user_name , Thanks For Your Registration . Your Configuration Code Is : " .
            $randomConfirmationCode  . ' .Al Roya Team ' ;

        $client = new Client($account_sid, $auth_token);

        $phoneService = new PhoneNumberService();
        $formattedNumber = $phoneService->formatNumber($phone , $countryCode);
        // +201025894984 for example
        $client->messages->create($formattedNumber,
            ['from' => $twilio_number, 'body' => $messageBody ] );
    }
}
