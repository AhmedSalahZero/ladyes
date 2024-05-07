<?php

namespace App\Http\Requests\Apis;
use App\Rules\CountryAndCityMustExistRule;
use App\Rules\HasEnoughAmountInHisWalletRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

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
	
		return [
			'amount'=>'required|gt:0',
			'country_must_exist_custom_rule'=>[new CountryAndCityMustExistRule()],
			'user_has_enough_amount_in_his_wallet'=>[new HasEnoughAmountInHisWalletRule]
		];
    }
	public function messages()
	{
		return [
			'amount.required'=>__('Please Enter :attribute' , ['attribute'=>__('Withdrawal Amount',[],getApiLang())]),
			'amount.gt'=> __('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Withdrawal Amount',[],getApiLang())]) ,
			'payment_method.required'=>__('Please Enter :attribute' , ['attribute'=>__('Payment Type',[],getApiLang())]),
			'payment_method.in'=>__('Invalid :attribute' , ['attribute'=>__('Payment Type')],getApiLang()),
		];
	}
	
	
}
