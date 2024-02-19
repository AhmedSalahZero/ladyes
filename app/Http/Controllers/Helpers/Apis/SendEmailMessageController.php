<?php

namespace App\Http\Controllers\Helpers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ResendVerificationCodeByEmailRequest;
use App\Http\Requests\Apis\SendEmailMessageRequest;
use App\Mail\SendMessageMail;
use App\Traits\Api\HasApiResponse;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageController extends Controller
{
	use HasApiResponse;
    public function send(SendEmailMessageRequest $request){
		$email = $request->get('email');
		$receiverName = $request->get('receiver_name');
		$subject = $request->get('subject');
		$textMessage = $request->get('message');
		try{
			Mail::to($email)->send(new SendMessageMail($email,$receiverName,$subject,$textMessage));
		}
		catch(\Exception $e){
			return $this->apiResponse(__('Fail To Send Message To Your Email Address',[],getApiLang()));
		}
		return $this->apiResponse(__('Message Has Been Sent To Your Email Address Successfully',[],getApiLang()));
	}
	public function resendVerificationCode(ResendVerificationCodeByEmailRequest $request){
		$modelType = $request->get('model_type');
		$model = ('\App\Models\\'.$modelType)::find($request->get('model_id'));
		$responseArr = $model->sendVerificationCodeViaEmail() ;
		$status = isset($responseArr['status']) && $responseArr['status'] ? 'success' : 'fail';
		$statusCode = $status == 'success' ? 200 : 500; 
		$message = isset($responseArr['message']) && $responseArr['message'] ? $responseArr['message'] : null;
		return $this->apiResponse($message , [],$statusCode);
	}
}
