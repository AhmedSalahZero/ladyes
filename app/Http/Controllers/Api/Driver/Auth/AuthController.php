<?php

namespace App\Http\Controllers\Api\Driver\Auth;


use App\Http\Requests\Apis\SendVerificationCodeRequest;
use App\Http\Requests\Apis\VerifyVerificationCodeRequest;
use App\Http\Requests\StoreDriverRequest;
use App\Models\Driver;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use App\Traits\Api\IsAuthController;
use Illuminate\Http\Request;

class AuthController 
{
	use HasApiResponse,IsAuthController ;

	public function register(StoreDriverRequest $request)
	{
		$model = new Driver();
		$model->syncFromRequest($request);
		$model->markAsVerified();
		if($request->has('received_invitation_code') && $request->get('received_invitation_code')){
			$code = $request->get('received_invitation_code') ;
			$sender = Driver::getByInvitationCode($code);
			$model->attachInvitationCode($sender->id ,$code );
		}
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
		return $this->handleSendingVerificationCode($request,'Driver');
	}
	
	/**
	 * * هنتاكد من ان الكود صح .. ولو الكود صح واليوزر دا موجود قبل كدا هنبعت الاوبجيكت بتاعه
	 */
	public function verifyVerificationCode(VerifyVerificationCodeRequest $request )
	{
		return $this->handleVerificationCode($request,'Driver');
	}
	public function logout(Request $request)
	{
		/**
		 * @var Driver $model 
		 */
		$model = $request->user('driver');
		$model->is_listing_to_orders_now = false ;
		$model->save();
		Notification::storeNewAdminNotification(
			__('New Logout', [], 'en'),
			__('New Logout', [], 'ar'),
			__('Driver :modelName Has Logged Out',['modelName'=>$model->getName('en')],'en'),
			__('Driver :modelName Has Logged Out',['modelName'=>$model->getName('ar')],'ar')
		);
		
		$model->tokens()->delete();
		return $this->apiResponse(__('Success Logout Attempt'));
	}
	
	
	
	
	
}
