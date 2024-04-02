<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Helpers\HHelpers;
use App\Http\Requests\Apis\LoginRequest;
use App\Http\Requests\Apis\SendVerificationCodeRequest;
use App\Http\Requests\Apis\VerifyVerificationCodeRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Country;
use App\Models\Driver;
use App\Models\Notification;
use App\Services\UserVerificationService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController 
{
	use HasApiResponse ;

	public function register(StoreClientRequest $request)
	{
		$model = new Client();
		$confirmationCodeResponseArr = $model->syncFromRequest($request);
		if(!$confirmationCodeResponseArr['status']){
			$model->delete();
			$errorMessage = $confirmationCodeResponseArr['message'] ;
			Notification::storeNewAdminNotification(
				__('Application Error !',[],'en'),
				__('Application Error !',[],'ar'),
				 $errorMessage,
				 $errorMessage,
			);
			return $this->apiResponse($errorMessage , [],Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		return $this->apiResponse($confirmationCodeResponseArr['message']);
	}
	/**
	 * * اول حاجه قبل ما نحدد ان كان المستخدم هيعمل لوجن ولا ريجيستر .. هناخد رقمة وكود الدولة ونتاكد
	 * * ان الرقم دا رقم هاتف صحيح
	 * * وبنبعتله كود تفعيل 
	 * 
	 */
	public function sendVerificationCode(SendVerificationCodeRequest $request , UserVerificationService $userVerificationService)
	{
		/**
		 * @var Client|Driver $model ;
		 */
		$countryIso2 = $request->get('country_iso2');
		$phone = $request->get('phone');
		$verificationCode =  $userVerificationService->renewCode($countryIso2,$phone,'Client');
		$responseArr = $userVerificationService->sendAsMessage($countryIso2,$phone,$verificationCode,null,null,true,true,false);
		return response()->json([
			'status'=>true ,
			'message'=>isset($responseArr['message']) ? $responseArr['message'] : null ,
			'data'=>[]
		]);
	}
	
	/**
	 * * هنتاكد من ان الكود صح .. ولو الكود صح واليوزر دا موجود قبل كدا هنبعت الاوبجيكت بتاعه
	 */
	public function verifyVerificationCode(VerifyVerificationCodeRequest $request , UserVerificationService $userVerificationService)
	{
		/**
		 * @var Client|Driver $model ;
		 */
		$countryIso2 = $request->get('country_iso2');
		$phone = $request->get('phone');
		$code = $request->get('verification_code');
		$validVerificationCode =  $userVerificationService->verify($code,$countryIso2,'Client');
		$client = Client::findByIdOrEmailOrPhone($phone);
		return response()->json([
			'status'=>$validVerificationCode ,
			'message'=>$validVerificationCode ? null : __('Invalid Verification Code',[],getApiLang()) ,
			'data'=>[
				'user_found'=>$userFound = (bool)$client,
				'user'=>$userFound ? new ClientResource($client) : null 
			]
		]);
	}
	
	
	// public function login(LoginRequest $request)
	// {
	// 	$model = new (HHelpers::getModelFullNameFromTableName());
	// 	return $model->loginByPhone($request);
	// }
	
	public function logout(Request $request)
	{
		/**
		 * @var Client $model 
		 */
		$model = $request->user('client');

		// Notification::storeNewAdminNotification(
		// 	__('New Logout', [], 'en'),
		// 	__('New Logout', [], 'ar'),
		// 	__('Client :modelName Has Logged Out',['modelName'=>$model->getName('en')],'en'),
		// 	__('Client :modelName Has Logged Out',['modelName'=>$model->getName('ar')],'ar')
		// );
		$model->tokens()->delete();
		// $model->renewVerificationCode();	
		return $this->apiResponse(__('Success Logout Attempt'));
	}
	
	
	
	
	
}
