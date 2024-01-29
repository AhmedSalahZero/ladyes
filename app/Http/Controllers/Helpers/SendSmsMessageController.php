<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Services\SMS\SmsService;
use Illuminate\Http\Request;

class SendSmsMessageController extends Controller
{
    public function send(Request $request){
		$phone = $request->get('phone');
		$countryCode = $request->get('country_code' );
		$message = $request->get('message');
		$responseArray = (new SmsService())->send($phone,$countryCode,$message);
		if($responseArray['status']){
			return redirect()->back()->with('success',__('Sms Message Has Been Sent Successfully') );
		}
		return redirect()->back()->with('fail',__('Fail To Send Sms Message') . ' ' . $responseArray['message'] );
	}
}
