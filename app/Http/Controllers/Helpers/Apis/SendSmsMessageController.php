<?php

namespace App\Http\Controllers\Helpers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeBySmsRequest;
use App\Http\Requests\Apis\SendSmsMessageRequest;
use App\Models\Country;
use App\Services\SMS\SmsService;
use App\Services\UserVerificationService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Response;

class SendSmsMessageController extends Controller
{
	use HasApiResponse;
    public function send(SendSmsMessageRequest $request){
		$phone = $request->get('phone');
		$countryCode = $request->get('country_code' );
		$message = $request->get('message');
		$country = Country::findByCode($countryCode);
		$responseArray = (new SmsService())->send($phone,$country->getIso2(),$message);
		if($responseArray['status']){
			return $this->apiResponse(__('Sms Message Has Been Sent To Your Phone Successfully',[],getApiLang()));
		}
		return $this->apiResponse(__('Fail To Send Sms Message To Your Phone',[],getApiLang()),[],Response::HTTP_INTERNAL_SERVER_ERROR);
	}
	public function resendVerificationCode(ResendVerificationCodeBySmsRequest $request,UserVerificationService $userVerificationService){
		$countryIso2 = $request->get('country_iso2') ;
		$phone = $request->get('phone');
		$verificationCode = $userVerificationService->renewCode($countryIso2,$phone,$request->get('user_type')) ;
		$responseArr = $userVerificationService->sendAsMessage($countryIso2,$phone,$verificationCode,null,null,true , false ,false);
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$statusCode = $status == 'success' ? 200 : 500; 
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return $this->apiResponse($message , [],$statusCode);
	}
}
