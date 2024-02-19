<?php

namespace App\Http\Controllers\Helpers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeBySmsRequest;
use App\Http\Requests\Apis\ResendVerificationCodeByWhatsappRequest;
use App\Http\Requests\Apis\SendWhatsappMessageRequest;
use App\Models\Country;
use App\Services\PhoneNumberService;
use App\Services\Whatsapp\WhatsappService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Response;

class SendWhatsappMessageController extends Controller
{
	use HasApiResponse;
    public function send(SendWhatsappMessageRequest $request){
		$phone = $request->get('phone');
		$countryCode = $request->get('country_code' );
		$message = $request->get('message');
		$country = Country::findByCode($countryCode);
		$phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone,$country->getIso2());
		$responseArray = App(WhatsappService::class)->sendMessage($message , $phoneFormatted);
		if($responseArray['status']){
			return $this->apiResponse(__('Message Has Been Sent To Your Whatsapp Successfully',[],getApiLang()));
		}
		return $this->apiResponse(__('Fail To Send Whatsapp Message To Your Phone',[],getApiLang()),[],Response::HTTP_INTERNAL_SERVER_ERROR);
	}
	public function resendVerificationCode(ResendVerificationCodeByWhatsappRequest $request){
		$modelType = $request->get('model_type');
		$model = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		$responseArr = $model->sendVerificationCodeMessage(false , true ,false) ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$statusCode = $status == 'success' ? 200 : 500; 
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return $this->apiResponse($message , [],$statusCode);
	}
	

}
