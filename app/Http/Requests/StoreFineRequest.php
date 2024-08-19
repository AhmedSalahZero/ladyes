<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Rules\CountryAndCityMustExistRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreFineRequest extends FormRequest
{ 
	use HasFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

   
    public function rules()
    {
	
		$model = $this->route('fine') ;
		$HValidationRules = HValidation::rules('fines', $model , Request::isMethod('post'));
        return 
		[
			'model_type'=>'required',
			'model_id'=>'required',
			'amount'=>$HValidationRules['amount'],
			'country_must_exist_custom_rule'=>[new CountryAndCityMustExistRule()]
        ];
    }
	public function messages()
	{
		return [
			
			'model_type.required'=>__('Please Enter :attribute' , ['attribute'=>__('User Type')]),
			'model_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('User Name')]),
			'amount.required'=>__('Please Enter :attribute' , ['attribute'=>__('Fine Amount')]),
			'amount.gt'=> __('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Fine Amount')]) ,
		];
	}
	
	
}
