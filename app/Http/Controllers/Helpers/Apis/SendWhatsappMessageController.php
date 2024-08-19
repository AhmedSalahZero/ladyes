<?php

namespace App\Http\Controllers\Helpers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeBySmsRequest;
use App\Http\Requests\Apis\ResendVerificationCodeByWhatsappRequest;
use App\Http\Requests\Apis\SendWhatsappMessageRequest;
use App\Models\Country;
use App\Services\PhoneNumberService;
use App\Services\UserVerificationService;
use App\Services\Whatsapp\WhatsappService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Response;

class SendWhatsappMessageController extends Controller
{
	use HasApiResponse;
    public function send(SendWhatsappMessageRequest $request){
		$phone = $request->get('phone');
		$countryIso2 = $request->get('country_iso2' );
		$message = $request->get('message');
		$phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone,$countryIso2);
		$responseArray = App(WhatsappService::class)->sendMessage($message , $phoneFormatted);
		if($responseArray['status']){
			return $this->apiResponse(__('Message Has Been Sent To Your Whatsapp Successfully',[],getApiLang()));
		}
		return $this->apiResponse(__('Fail To Send Whatsapp Message To Your Phone',[],getApiLang()),[],Response::HTTP_INTERNAL_SERVER_ERROR);
	}
	public function resendVerificationCode(ResendVerificationCodeByWhatsappRequest $request , UserVerificationService $userVerificationService){
		
		$countryIso2 = $request->get('country_iso2') ;
		$phone = $request->get('phone');
		$verificationCode = $userVerificationService->renewCode($countryIso2,$phone,$request->get('user_type')) ;
		$responseArr = $userVerificationService->sendAsMessage($countryIso2,$phone,$verificationCode,null,null,false , true ,false);
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$statusCode = $status == 'success' ? 200 : 500; 
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return $this->apiResponse($message , [],$statusCode);
	}
	

}
