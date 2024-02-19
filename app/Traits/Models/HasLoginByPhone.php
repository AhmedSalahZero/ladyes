<?php 
namespace App\Traits\Models;

use App\Models\Country;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

trait HasLoginByPhone 
{
	use HasApiResponse;
	public function loginByPhone(Request $request)
	{
		$country = Country::findByCode($request->get('country_code'));
		$model = self::findByCountryIdAndPhone($country->id , $request->get('phone'));
		if($model->isBanned()){
			return $this->apiResponse(__('Your Account Has Been Disabled , If You Think Your Account Was Disabled By Mistake , Please Contact With Us',[],getApiLang()));
		}
		if($request->boolean('resend_verification_code')){
			$model->renewVerificationCode();	
			$viaSms = $request->get('send_verification_code_by') == 'sms';
			$viaWhatsapp = $request->get('send_verification_code_by') == 'whatsapp';
			$model->sendVerificationCodeMessage($viaSms,$viaWhatsapp,false,true);
			return $this->apiResponse(__('Verification Code Has Been Sent Successfully',[],getApiLang()));
		}
		if($model->sendNotificationToAdminAfterLogin()){
			Notification::storeNewNotification(
				__('New Login', [], 'en'),
				__('New Login', [], 'ar'),
				__('Driver :modelName Has Logged In',['modelName'=>$model->getName('en')],'en'),
				__('Driver :modelName Has Logged In',['modelName'=>$model->getName('ar')],'ar')
			);
		}
		$model->tokens()->delete();
		return $this->apiResponse(__('Success Login Attempt',[],getApiLang()),[
			'access_token'=>$model->createToken('personal_access_token')->plainTextToken
		]);
	}
	
	public static function findByCountryIdAndPhone(int $countryId , string $phone):?self{
		return static::where('country_id',$countryId)->where('phone',$phone)->first();
	} 
	
	
}
