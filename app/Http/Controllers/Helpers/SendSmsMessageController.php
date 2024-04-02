<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeBySmsRequest;
use App\Services\SMS\SmsService;
use Illuminate\Http\Request;

class SendSmsMessageController extends Controller
{
    public function send(Request $request){
		$phone = $request->get('phone');
		$countryIso2 = $request->get('country_code' );
		$message = $request->get('message');
		
		$responseArray = (new SmsService())->send($phone,$countryIso2,$message);
		if($responseArray['status']){
			return redirect()->back()->with('success',__('Sms Message Has Been Sent Successfully') );
		}
		return redirect()->back()->with('fail',__('Fail To Send Sms Message') . ' ' . $responseArray['message'] );
	}
	public function resendVerificationCode(ResendVerificationCodeBySmsRequest $request)
	{
		$modelType = $request->get('model_type');
		$model = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		if(!$model){
			return redirect()->back()->with('fail',__($modelType.' Not Found'));
		}
		$responseArr = $model->sendVerificationCodeMessage($model->getVerificationCode(),true , false ,false) ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return redirect()->back()->with($status,$message);
	}
}
