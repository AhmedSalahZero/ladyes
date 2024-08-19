<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Services\PhoneNumberService;
use App\Services\Whatsapp\WhatsappService;
use Illuminate\Http\Request;

class SendWhatsappMessageController extends Controller
{
    public function send(Request $request){
		$phone = $request->get('phone');
		$countryCode = $request->get('country_code' );
		$message = $request->get('message');
		$phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone,$countryCode);
		$responseArray = App(WhatsappService::class)->sendMessage($message , $phoneFormatted);
		if($responseArray['status']){
			return redirect()->back()->with('success',__('Whatsapp Message Has Been Sent Successfully') );
		}
		return redirect()->back()->with('fail',__('Fail To Send Whatsapp Message') . ' ' . $responseArray['message'] );
	}
}
