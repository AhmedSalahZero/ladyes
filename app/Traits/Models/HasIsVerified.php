<?php

namespace App\Traits\Models;

use App\Helpers\HHelpers;
use App\Mail\SendMessageMail;
use App\Services\PhoneNumberService;
use App\Services\SMS\SmsService;
use App\Services\Whatsapp\WhatsappService;
use Exception;
use Illuminate\Support\Facades\Mail;

trait HasIsVerified
{
    public function scopeOnlyIsVerified($q)
    {
        return $q->where('is_verified', 1);
    }

    public function getIsVerified()
    {
        return (bool) $this->is_verified ;
    }

    public function getIsVerifiedFormatted()
    {
        $isVerified = $this->getIsVerified();

        return $isVerified ? __('Yes') : __('No');
    }

    public function toggleIsVerified()
    {
        $this->is_verified = !$this->is_verified ;
        $this->save();
    }

    public function sendVerificationCodeViaEmail(): array
    {
        $email = $this->getEmail();
        $receiverName = $this->getFullName();
        $subject = __('Verification Code');
        $textMessage = $this->generateVerificationCodeMessage();

        try {
            Mail::to($email)->send(new SendMessageMail($email, $receiverName, $subject, $textMessage));
        } catch(Exception $e) {
            return [
                'status' => false,
                'message' => __('Failed To Send Email Message') . ' <br> ' . $e->getMessage()
            ];
        }

        return [
            'status' => true,
            'message' => __('Verification Code Has Been Sent To Your Email')
        ];
    }

    public function sendVerificationCodeMessage(bool $viaSms = true, bool $viaWhatsapp = true, bool $viaEmail = true,bool $forceSend = false): array
    {
        if ($this->getIsVerified() && !$forceSend) {
            return [];
        }
        $phone = $this->getPhone();
        $countryCode = $this->getCountryIso2();
        $message = $this->generateVerificationCodeMessage();
        $phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone, $countryCode);
        if ($viaSms) {
            $responseArray = (new SmsService())->send($phone, $countryCode, $message);
            if ($responseArray['status'] && $responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Phone Number')
                ];
            }
        }
        if ($viaWhatsapp) {
            $responseArray = App(WhatsappService::class)->sendMessage($message, $phoneFormatted);
            if ($responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Whatsapp')
                ];
            }
        }

        if ($viaEmail) {
            $responseArray = $this->sendVerificationCodeViaEmail();
            if ($responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Email')
                ];
            }
        }

        return [
            'status' => false,
            'message' => isset($responseArray['message']) ? __('Fail To Send Verification Code') . ' ' . $responseArray['message'] : __('Fail To Send Verification Code')
        ];
    }

    public function generateVerificationCodeMessage()
    {
        if (app()->getLocale() == 'ar') {
            return 'مرحبا ' . $this->getFullName() . ' رمز التفعيل الخاص بك هو ' . $this->getVerificationCode() . ' برجاء ادخال هذا الكود في التطبيق لتفعيل حسابك  . اذا كان لديك اي استفسار تواصل معنا عبر' . getSetting('email') . ' سعدنا جدا بانضمامك الينا ..  فريق   ' . getSetting('app_name_ar') ;
        }

        return 'Hi ' . $this->getFullName() . ' 
		Your verification code is' . $this->getVerificationCode() . '
		
		Enter this code in our app to activate your account.
		
		If you have any questions, send us an email ' . getSetting('email') . '.
		
		We’re glad you’re here!
		The ' . getSetting('app_name_' . app()->getLocale()) . ' team';
    }

    public function storeVerificationCodeIfNotExist()
    {
        if ($this->verification_code) {
            return $this ;
        }
        $this->renewVerificationCode();
        return $this ;
    }
	public function renewVerificationCode()
    {
        $this->verification_code = $this->generateRandomVerificationCode();
        $this->save();
        return $this ;
    }
	public function generateRandomVerificationCode()
	{
		return random_int(1000, 9999) ;
	}
}
