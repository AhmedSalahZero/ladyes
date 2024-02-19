<?php

namespace App\Rules;

use DB;
use Illuminate\Contracts\Validation\Rule;

class VerificationCodeExistForPhoneRule implements Rule
{
	protected $phone ,$tableName ;
    public function __construct($tableName,$phone)
    {
		$this->phone = $phone ;
		$this->tableName = strtolower($tableName) ;
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
        return  DB::table($this->tableName)->where('phone',$this->phone)->where($attribute,$value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please Enter Valid Verification Code',[],getApiLang());
    }
}
