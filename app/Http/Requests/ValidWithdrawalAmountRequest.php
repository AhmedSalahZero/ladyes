<?php

namespace App\Http\Requests;

use App\Rules\CountryAndCityMustExistRule;
use App\Rules\HasEnoughAmountInHisWalletRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidWithdrawalAmountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
			'amount.required'=>__('Please Enter :attribute' , ['attribute'=>__('Amount')]),
			'amount.gt'=>__('Only Greater Than Zero Allowed For :attribute',['attribute'=>__('Amount')])
		];
	}
}
