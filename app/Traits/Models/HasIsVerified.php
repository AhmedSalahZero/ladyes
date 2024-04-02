<?php

namespace App\Traits\Models;

use App\Helpers\HHelpers;
use App\Mail\SendMessageMail;
use App\Models\VerificationCode;
use App\Services\PhoneNumberService;
use App\Services\SMS\SmsService;
use App\Services\UserVerificationService;
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

    public function sendVerificationCodeViaEmail($email,$receiverName,$verificationCode): array
    {
        return (new UserVerificationService())->sendViaEmail($email,$receiverName,$verificationCode);
    }

    public function sendVerificationCodeMessage($verificationCode,string $fullName = null , string $email = null ,bool $viaSms = true, bool $viaWhatsapp = true, bool $viaEmail = true,bool $forceSend = false): array
    {
		$phone = $this->getPhone();
        $countryCode = $this->getCountryIso2();
		
        if ($this->getIsVerified() && !$forceSend) {
            return [];
        }
        return (new UserVerificationService())->sendAsMessage($phone, $countryCode,$verificationCode, $fullName ,  $email  , $viaSms ,  $viaWhatsapp ,  $viaEmail , $forceSend);
    }

    // public function storeVerificationCodeIfNotExist()
    // {
    //     if ($this->verification_code) {
    //         return $this ;
    //     }
    //     $this->renewVerificationCode();
    //     return $this ;
    // }
	public function renewVerificationCode():self
    {
	   (new UserVerificationService())->renewCode($this->getCountryIso2(),$this->getPhone(),HHelpers::getClassNameWithoutNameSpace($this));
	   return $this;
        
    }
	public function verificationCode()
	{
		return $this->belongsTo(VerificationCode::class,'phone','phone')->where('country_iso2',$this->getCountryIso2());
	}
	public function getVerificationCode()
	{
		return $this->verificationCode ?$this->verificationCode->code : (new UserVerificationService())->renewCode($this->getCountryIso2(),$this->getPhone(),HHelpers::getClassNameWithoutNameSpace($this));
	}
}
