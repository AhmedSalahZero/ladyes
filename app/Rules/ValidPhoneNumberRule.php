<?php

namespace App\Rules;

use App\Models\Country;
use App\Services\PhoneNumberService;
use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumberRule implements Rule
{
 
	protected ?Country $country ; 
	protected phoneNumberService $phoneNumberService ; 
	
    public function __construct( $countryId)
    {
		$this->country = Country::find($countryId) ; 
		if(!$this->country){
			$this->country = Country::findByIso2($countryId);
		}
		$this->phoneNumberService = new PhoneNumberService();
    }


    public function passes($attribute, $phone)
    {
		return $this->country && $this->phoneNumberService->isValidNumber($phone,$this->country->getIso2());
    }
    public function message()
    {
        return __('Invalid Phone Number');
    }
}
