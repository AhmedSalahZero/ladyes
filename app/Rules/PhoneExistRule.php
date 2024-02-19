<?php

namespace App\Rules;

use App\Models\Country;
use App\Models\Driver;
use Illuminate\Contracts\Validation\Rule;

class PhoneExistRule implements Rule
{
	protected ?string $countryCode ;
	protected ?string $phone ;
	 
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?string $countryCode , ?string $phone)
    {
		$this->countryCode = $countryCode ;
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
		$country = Country::findByCode($this->countryCode);
		return $country && Driver::findByCountryIdAndPhone($country->id , $value);
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
