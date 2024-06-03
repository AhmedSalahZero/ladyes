<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TwoArrayMustHaveSameLengthRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	protected $firstArrayKey , $secondArrayKey , $failedMessage; 
    public function __construct(string $failedMessage ,$firstArrayKey , $secondArrayKey)
    {
       $this->firstArrayKey = $firstArrayKey ;
	   $this->secondArrayKey = $secondArrayKey;
	   $this->failedMessage = $failedMessage;
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
        $firstArr = Request($this->firstArrayKey);
        $secondArr = Request($this->secondArrayKey);
		if(is_array($firstArr) && is_array($secondArr) && count($firstArr) != count($secondArr) ){
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
        return $this->failedMessage ;;
    }
}
