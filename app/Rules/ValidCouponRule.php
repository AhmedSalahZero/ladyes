<?php

namespace App\Rules;

use App\Models\Coupon;
use Illuminate\Contracts\Validation\Rule;

class ValidCouponRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
	protected $errorMessage ;
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
        $coupon = Coupon::findByCode($value);
		if(!$coupon){
			$this->errorMessage = __('This Coupon Not Exist',[],getApiLang());
			return false ;
		}
		if(!$coupon->canBeAppliedForClient(Request('client_id',0))){
			$this->errorMessage = __('This Coupon In Expired',[],getApiLang());
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
        return $this->errorMessage;
    }
}
