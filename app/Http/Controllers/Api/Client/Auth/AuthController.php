<?php

namespace App\Http\Controllers\Api\Client\Auth;

use App\Helpers\HHelpers;
use App\Http\Requests\Apis\LoginRequest;
use App\Http\Requests\Apis\LogoutClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Models\Notification;
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
		
		// Notification::storeNewAdminNotification(
		// 	__('New Creation', [], 'en'),
		// 	__('New Creation', [], 'ar'),
		// 	__('New :modelName Has Been Registered',['modelName'=>$model->getName('en')],'en'),
		// 	__('New :modelName Has Been Registered',['modelName'=>$model->getName('ar')],'ar')
		// );

		return $this->apiResponse($confirmationCodeResponseArr['message']);
		// return $this->apiResponse($confirmationCodeResponseArr['message'],[
		// 	'access_token'=>$model->createToken('personal_access_token')->plainTextToken
		// ]);
	}
	
	public function login(LoginRequest $request)
	{
		$model = new (HHelpers::getModelFullNameFromTableName());
		return $model->loginByPhone($request);
	}
	
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
		$model->renewVerificationCode();	
		return $this->apiResponse(__('Success Logout Attempt'));
	}
	
	
	
	
	
}
