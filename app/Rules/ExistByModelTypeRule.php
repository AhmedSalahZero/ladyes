<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistByModelTypeRule implements Rule
{
	protected $modelType;
    public function __construct(?string $modelType)
    {
		$this->modelType = $modelType ; 
    }
    public function passes($attribute, $value)
    {
		if(!$this->modelType){
			return false ;
		}
        return ('App\Models\\'.$this->modelType)::find($value);
    }
    public function message()
    {
        return __('This Record Not Found',[],getApiLang());
    }
}
