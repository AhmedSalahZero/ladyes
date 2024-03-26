<?php

namespace App\Http\Requests;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateCountriesRequest extends FormRequest
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

	
        return 
		[
			'fees.*'=>'required',
			'fees.*'=>'required|numeric|gte:0'
        ];
    }
	public function messages()
	{
		return [
			'fees.*.required'=>__('Please Enter :attribute' , ['Prices'=>__('Fees Amount')]),
			'fees.*.required'=>__('Please Enter :attribute' , ['Prices'=>__('Fees Amount')]),
			'fees.*.numeric'=>__('Invalid :attribute' , ['Prices'=>__('Fees Amount')]),
			'fees.*.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Fees Amount')]),
		];
	}
	
	
}
