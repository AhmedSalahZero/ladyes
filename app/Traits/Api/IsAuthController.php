<?php 
namespace App\Traits\Api;

use App\Services\UserVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait IsAuthController
{
	public function handleSendingVerificationCode(Request $request , string $userType):JsonResponse
	{
		/**
		* @var Client|Driver $model ;
		*/
		$userVerificationService = new UserVerificationService ;
	   $countryIso2 = $request->get('country_iso2');
	   $phone = $request->get('phone');
	   $verificationCode =  $userVerificationService->renewCode($countryIso2,$phone,$userType);
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
	public function handleVerificationCode(Request $request , string $userType)
	{
		/**
		 * @var Client|Driver $model ;
		 */
		$fullClassName = '\App\Models\\'.$userType ;
		$userVerificationService = new UserVerificationService ;
		$countryIso2 = $request->get('country_iso2');
		$phone = $request->get('phone');
		$code = $request->get('verification_code');
		$validVerificationCode =  $userVerificationService->verify($code,$countryIso2,$userType);
		$user = $fullClassName::findByIdOrEmailOrPhone($phone);
		$userFound = (bool)$user ; 
		$userFound ? $user->tokens()->delete() : null;
		$validVerificationCode && $userFound  ? $user->markAsVerified() : null ;
		return response()->json([
			'status'=>$validVerificationCode ,
			'message'=>$validVerificationCode ? __('Success !') : __('Invalid Verification Code',[],getApiLang()) ,
			'data'=>[
				'user_found'=>$userFound ,
				'user'=>$userFound ? $user->getResource() : null  ,
			]
			]);
	}
}
