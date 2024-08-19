<?php 
namespace App\Traits\Models;


trait HasSecureTravel 
{
	 /**
	  * * هل هو مفعل نظام الرحلة الامنة ولا لا ؟ لان لو هو مفعلة فا اثناء انشاء الرحلة هيتم انشاء رمز امان لها
	  * * بحيث العميل  يتاكد من ان هذا السائق هو السائق الحقيقي
     */
    public function getHasSecureTravel()
    {
        return (bool) $this->has_secure_travel ;
    }

    public function getHasSecureTravelFormatted()
    {
        $isSecure = $this->getHasSecureTravel();

        return $isSecure ? __('Yes') : __('No');
    }
	
	
}
