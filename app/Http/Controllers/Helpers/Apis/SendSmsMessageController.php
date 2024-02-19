<?php

namespace App\Http\Controllers\Helpers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeBySmsRequest;
use App\Http\Requests\Apis\SendSmsMessageRequest;
use App\Models\Country;
use App\Services\SMS\SmsService;
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
	public function resendVerificationCode(ResendVerificationCodeBySmsRequest $request){
		$modelType = $request->get('model_type');
		$model = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		$responseArr = $model->sendVerificationCodeMessage(true , false ,false) ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$statusCode = $status == 'success' ? 200 : 500; 
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return $this->apiResponse($message , [],$statusCode);
	}
}
