<?php

namespace App\Services;

use App\Mail\SendMessageMail;
use App\Models\VerificationCode;
use App\Services\SMS\SmsService;
use App\Services\Whatsapp\WhatsappService;
use Exception;
use Illuminate\Support\Facades\Mail;

class UserVerificationService
{
	/**
	 * * هل كود التفعيل اللي اليوزر دخله دا صح ولا غلط
	 */
	public function verify(string $code,string $countryIso,string $userType):bool
	{
		return VerificationCode::where('code',$code)->where('user_type',$userType)->where('country_iso2',$countryIso)->exists();
	}
    public function renewCode(string $countryIso2, string $phone,string $userType):string
    {
        $verificationCode = $this->generateRandomVerificationCode();
        $oldOneExist = VerificationCode::where('country_iso2', $countryIso2)->where('phone', $phone)->exists();
		
        if ($oldOneExist) {
            VerificationCode::where('country_iso2', $countryIso2)->where('user_type',$userType)->where('phone', $phone)->update([
                'code' => $verificationCode,
            ]);
            return  $verificationCode;
        }
        VerificationCode::create([
            'code' => $verificationCode,
            'country_iso2' => $countryIso2,
            'phone' => $phone,
			'user_type'=>$userType
        ]);

        return $verificationCode ;
    }

    public function generateRandomVerificationCode()
    {
        return random_int(1000, 9999) ;
    }

    public function generateCodeMessage($verificationCode, string $userFullName = null)
    {
        $userFullName = is_null($userFullName) ? '' : $userFullName ;
        if (getApiLang() == 'ar') {
            $userFullName = $userFullName ? 'مرحبا ' . $userFullName : ''  ;

            return $userFullName . ' رمز التفعيل الخاص بك هو ' . $verificationCode . ' برجاء ادخال هذا الكود في التطبيق لتفعيل حسابك  . اذا كان لديك اي استفسار تواصل معنا عبر' . getSetting('email') . ' سعدنا جدا بانضمامك الينا ..  فريق   ' . getSetting('app_name_ar') ;
        }
        $userFullName = $userFullName ?: '' ;

        return  $userFullName ? 'Hi ' . $userFullName : '' . ' 
		Your verification code is' . $verificationCode . '
		
		Enter this code in our app to activate your account.
		
		If you have any questions, send us an email ' . getSetting('email') . '.
		
		We’re glad you’re here!
		The ' . getSetting('app_name_' . app()->getLocale()) . ' team';
    }

    public function sendAsMessage(string $countryCode,string $phone, string $verificationCode, string $fullName = null, string $email = null, bool $viaSms = true, bool $viaWhatsapp = true, bool $viaEmail = true): array
    {
        $message = $this->generateCodeMessage($verificationCode, $fullName);
        $phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone, $countryCode);
        if ($viaSms) {
            $responseArray = (new SmsService())->send($phone, $countryCode, $message);
            if ($responseArray['status'] && $responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Phone Number',[],getApiLang())
                ];
            }
        }
        if ($viaWhatsapp) {
            $responseArray = App(WhatsappService::class)->sendMessage($message, $phoneFormatted);
            if ($responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Whatsapp',[],getApiLang())
                ];
            }
        }

        if ($viaEmail) {
            $responseArray = $this->sendViaEmail($email, $fullName, $verificationCode);
            if ($responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Email',[],getApiLang())
                ];
            }
        }

        return [
            'status' => false,
            'message' => isset($responseArray['message']) ? __('Fail To Send Verification Code',[],getApiLang()) . ' ' . $responseArray['message'] : __('Fail To Send Verification Code',[],getApiLang())
        ];
    }

    public function sendViaEmail($email, $receiverName, $verificationCode): array
    {
        $subject = __('Verification Code',[],getApiLang());
        $textMessage = $this->generateCodeMessage($verificationCode);

        try {
            Mail::to($email)->send(new SendMessageMail($email, $receiverName, $subject, $textMessage));
        } catch(Exception $e) {
            return [
                'status' => false,
                'message' => __('Failed To Send Email Message',[],getApiLang()) . ' <br> ' . $e->getMessage()
            ];
        }

        return [
            'status' => true,
            'message' => __('Verification Code Has Been Sent To Your Email',[],getApiLang())
        ];
    }
}
