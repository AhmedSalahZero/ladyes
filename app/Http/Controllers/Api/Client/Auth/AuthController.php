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
use App\Traits\Api\IsAuthController;
use Illuminate\Http\Request;

class AuthController 
{
	use HasApiResponse , IsAuthController;

	public function register(StoreClientRequest $request)
	{
		$model = new Client();
		 $model->syncFromRequest($request);
		 $model->markAsVerified();
		 Notification::storeNewAdminNotification(
			__('New Creation', [], 'en'),
			__('New Creation', [], 'ar'),
			__('New :modelName Has Been Registered',['modelName'=>$model->getName('en')],'en'),
			__('New :modelName Has Been Registered',['modelName'=>$model->getName('ar')],'ar')
		);
		return $this->apiResponse(__('You Account Has Been Registered',[],getApiLang()),[
			'user'=>$model->getResource(),
		]);
	}
	/**
	 * * اول حاجه قبل ما نحدد ان كان المستخدم هيعمل لوجن ولا ريجيستر .. هناخد رقمة وكود الدولة ونتاكد
	 * * ان الرقم دا رقم هاتف صحيح
	 * * وبنبعتله كود تفعيل 
	 * 
	 */
	public function sendVerificationCode(SendVerificationCodeRequest $request )
	{
		return $this->handleSendingVerificationCode($request,'Client');
	}
	
	/**
	 * * هنتاكد من ان الكود صح .. ولو الكود صح واليوزر دا موجود قبل كدا هنبعت الاوبجيكت بتاعه
	 */
	public function verifyVerificationCode(VerifyVerificationCodeRequest $request )
	{
		return $this->handleVerificationCode($request,'Client');
	}
	
	public function logout(Request $request)
	{
		$model = $request->user('client');
		$model->tokens()->delete();
		return $this->apiResponse(__('Success Logout Attempt'));
	}
	
	
	
	
	
}
