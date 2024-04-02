<?php

namespace App\Rules;

use App\Helpers\HHelpers;
use App\Models\Country;
use Illuminate\Contracts\Validation\Rule;

class UserIsActiveAndIfExistRule implements Rule
{
	protected ?string $countryIso2 ;
	protected ?string $phone ;
	 
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?string $countryIso2 , ?string $phone)
    {
		$this->countryIso2 = $countryIso2 ;
		$this->phone = $phone ;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		$tableName = HHelpers::getTableNameFromRequest();
		$isDriver = $tableName == 'drivers'  ;
		$modelName = $isDriver ? 'Driver' : 'Client';
		$country = Country::findByIso2($this->countryIso2);
		if(!$country){
			return false ;
		}
		$user = ('\App\Models\\'.$modelName)::findByCountryIdAndPhone($country->id , $value) ;
		if($user){
			return !$user->isBanned();
		}
		return true  ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Your Account Has Been Disabled , If You Think Your Account Was Disabled By Mistake , Please Contact With Us',[],getApiLang());
    }
}
