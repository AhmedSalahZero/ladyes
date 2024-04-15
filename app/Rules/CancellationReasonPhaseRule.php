<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class CancellationReasonPhaseRule implements ImplicitRule
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

        if(Request('model_type') == 'Driver'){
			return true ;
		}
		if(Request('model_type') == 'Client' && $value){
			return true ;
		}
		return false ; 
		
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please Enter :attribute',['attribute'=>__('Phase')],getApiLang());
    }
}
