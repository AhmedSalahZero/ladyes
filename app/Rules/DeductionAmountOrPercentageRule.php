<?php

namespace App\Rules;

use App\Enum\DeductionType;
use App\Enum\DiscountType;
use Illuminate\Contracts\Validation\Rule;

class DeductionAmountOrPercentageRule implements Rule
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
		$discountType = Request('deduction_type');
		$isValidDiscount = is_numeric($value) && $value > 0 ; 
		if($discountType == DeductionType::PERCENTAGE){
			return  $isValidDiscount && $value <= 100 ; 
		}
		return $isValidDiscount ; 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Deduction Value Incorrect');
    }
}
