<?php

namespace App\Rules;

use App\Helpers\HAuth;
use App\Helpers\HHelpers;
use Illuminate\Contracts\Validation\ImplicitRule;

class CountryAndCityMustExistRule implements ImplicitRule
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
		$user = Request()->user(HAuth::getActiveGuard()) ;
		$modelType = HHelpers::getClassNameWithoutNameSpace($user);
		$modelType = Request('model_type',$modelType);
		if(!$modelType){
			$this->failedMessage = __('User Not Found !',[],getApiLang());
			return false ;
		}
        $fullClassName = '\App\Models\\'.$modelType;
		$user = $fullClassName::find(Request('model_id',$user->id));
		if(!$user){
			$this->failedMessage = __('User Not Found !',[],getApiLang());
			return false ;
		}
		if(!$user->getCountry()){
			$this->failedMessage = __('Country Not Found For This User',[],getApiLang()) ;
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
