<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class HasEnoughAmountInHisWalletRule implements ImplicitRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */	
	protected $failedMessage = null ;
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
		$modelType = Request('model_type');
		if(!$modelType){
			$this->failedMessage = __('User Not Found !');
			return false ;
		}
        $fullClassName = '\App\Models\\'.$modelType;
		$user = $fullClassName::find(Request('model_id'));
		if(!$user){
			$this->failedMessage = __('User Not Found !');
			return false ;
		}
		if(Request('amount') > $user->getTotalWalletBalance()){
			$this->failedMessage = __('User Has Not Enough Balance');
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
        return $this->failedMessage;
    }
}
