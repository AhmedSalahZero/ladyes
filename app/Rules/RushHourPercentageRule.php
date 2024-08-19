<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RushHourPercentageRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
		$explodedValue = explode('/',$value);
		if(!isset($explodedValue[0]) ||  ! isset($explodedValue[1]) ){
			return false ;
		}
		if(! is_numeric($explodedValue[0]) || !is_numeric($explodedValue[1])){
			return false ;
		}
		if($explodedValue[0] > 5 || $explodedValue[0] <= 0 || $explodedValue[1] > 5 || $explodedValue[1] <= 0){
			return false ;
		}
		return true ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Invalid Value For Rush Hour Percentage .. put it [ 2/5 for example ]') ; 
    }
}
