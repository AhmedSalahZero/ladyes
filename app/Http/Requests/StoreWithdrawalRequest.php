<?php

namespace App\Http\Requests;
use App\Enum\PaymentType;
use App\Helpers\HHelpers;
use App\Helpers\HValidation;
use App\Rules\CountryAndCityMustExistRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreWithdrawalRequest extends FormRequest
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

		$model = $this->route('withdrawal') ;
		$HValidationRules = HValidation::rules('withdrawals', $model , Request::isMethod('post'));
        return 
		[
			'model_type'=>'required',
			'model_id'=>'required',
			'amount'=>$HValidationRules['amount'],
			'country_must_exist_custom_rule'=>[new CountryAndCityMustExistRule()],
			'payment_method'=>['sometimes','required','in:'.implode(',',array_keys(PaymentType::all()))],
        ];
    }
	public function messages()
	{
		return [
			'amount.required'=>__('Please Enter :attribute' , ['attribute'=>__('Amount',[],getApiLang())]),
			'amount.gt'=>__('Only Greater Than Zero Allowed For :attribute',['attribute'=>__('Amount',[],getApiLang())])
		];
	}
	
	
}
