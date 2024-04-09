<?php

namespace App\Http\Requests;

use App\Rules\CountryAndCityMustExistRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidAmountRequest extends FormRequest
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
			'country_must_exist_custom_rule'=>[new CountryAndCityMustExistRule()]
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
