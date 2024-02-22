<?php

namespace App\Http\Controllers\Api\Driver\Auth;

use App\Helpers\HHelpers;
use App\Http\Requests\Apis\LoginRequest;
use App\Http\Requests\Apis\LogoutDriverRequest;
use App\Http\Requests\StoreDriverRequest;
use App\Models\Driver;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController 
{
	use HasApiResponse ;

	public function register(StoreDriverRequest $request)
	{
		$model = new Driver();
		$confirmationCodeResponseArr = $model->syncFromRequest($request);
		if($request->has('received_invitation_code')){
			$code = $request->get('received_invitation_code') ;
			$sender = Driver::getByInvitationCode($code);
			$model->attachInvitationCode($sender->id ,$code );
		}
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
		
		Notification::storeNewAdminNotification(
			__('New Creation', [], 'en'),
			__('New Creation', [], 'ar'),
			__('New :modelName Has Been Registered',['modelName'=>$model->getName('en')],'en'),
			__('New :modelName Has Been Registered',['modelName'=>$model->getName('ar')],'ar')
		);

		return $this->apiResponse($confirmationCodeResponseArr['message']);
		
		return $this->apiResponse($confirmationCodeResponseArr['message'],[
			'access_token'=>$model->createToken('personal_access_token')->plainTextToken
		]);
	}
	
	public function login(LoginRequest $request)
	{
		$model = new (HHelpers::getModelFullNameFromTableName());
		return $model->loginByPhone($request);
	}
	
	public function logout(Request $request)
	{
		/**
		 * @var Driver $model 
		 */
		$model = $request->user('driver');
		Notification::storeNewAdminNotification(
			__('New Logout', [], 'en'),
			__('New Logout', [], 'ar'),
			__('Driver :modelName Has Logged Out',['modelName'=>$model->getName('en')],'en'),
			__('Driver :modelName Has Logged Out',['modelName'=>$model->getName('ar')],'ar')
		);
		
		$model->tokens()->delete();
		$model->renewVerificationCode();	
		return $this->apiResponse(__('Success Logout Attempt'));
	}
	
	
	
	
	
}
