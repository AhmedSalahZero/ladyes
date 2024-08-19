<?php 
namespace App\Traits\Models;

use App\Models\Country;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

trait HasLoginByPhone 
{
	use HasApiResponse;
	public function getCurrentToken()
	{
		$token = $this->currentAccessToken();
		if(!$token){
			return $this->createToken('personal_access_token')->plainTextToken ;
		}
		return Request()->bearerToken() ;
		
	}
	public function loginByPhone(Request $request )
	{
		$country = Country::findByCode($request->get('country_code'));
		$user = self::findByCountryIdAndPhone($country->id , $request->get('phone'));
		if($user->isBanned()){
			return $this->apiResponse(__('Your Account Has Been Disabled , If You Think Your Account Was Disabled By Mistake , Please Contact With Us',[],getApiLang()));
		}
		if($request->boolean('resend_verification_code')){
			return $this->sendVerificationCode($user,$request);
		}
		if($user->sendNotificationToAdminAfterLogin()){
			Notification::storeNewAdminNotification(
				__('New Login', [], 'en'),
				__('New Login', [], 'ar'),
				__('Driver :modelName Has Logged In',['modelName'=>$user->getName('en')],'en'),
				__('Driver :modelName Has Logged In',['modelName'=>$user->getName('ar')],'ar')
			);
		}
		$user->tokens()->delete();
		return $this->apiResponse(__('Success Login Attempt',[],getApiLang()),[
			'access_token'=>$user->createToken('personal_access_token')->plainTextToken
		]);
	}
	public function sendVerificationCode($user,$request)
	{
			$verificationCode = $user->renewVerificationCode();	
			$viaSms = $request->get('send_verification_code_by','sms') == 'sms';
			$viaWhatsapp = $request->get('send_verification_code_by') == 'whatsapp';
			$user->sendVerificationCodeMessage($verificationCode,$viaSms,$viaWhatsapp,false,true);
			return $this->apiResponse(__('Verification Code Has Been Sent Successfully',[],getApiLang()));
	}
	
	public static function findByCountryIdAndPhone(int $countryId , string $phone):?self{
		return static::where('country_id',$countryId)->where('phone',$phone)->first();
	} 
	
	
}
