<?php

namespace App\Rules;

use App\Helpers\HHelpers;
use App\Models\Country;
use Illuminate\Contracts\Validation\Rule;

class PhoneExistRule implements Rule
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
		$country = Country::findByIso2($this->countryIso2);
		$tableName = HHelpers::getTableNameFromRequest();
		$isDriver = $tableName == 'drivers'  ;
		$modelName = $isDriver ? 'Driver' : 'Client';
		if(!$country){
			return false ;
		}
		return ('\App\Models\\'.$modelName)::findByCountryIdAndPhone($country->id , $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('This Phone Number Does Not Exist In Our Records');
    }
}
