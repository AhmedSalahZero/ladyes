<?php

namespace App\Http\Requests;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class updateCarSizePricesRequest extends FormRequest
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
			'prices.*'=>'required',
			'prices.*'=>'required|numeric'
        ];
    }
	public function messages()
	{
		return [
			'prices.*.required'=>__('Please Enter :attribute' , ['Prices'=>__('Prices')]),
			'prices.*.required'=>__('Please Enter :attribute' , ['Prices'=>__('Prices')]),
			'prices.*.numeric'=>__('Invalid :attribute' , ['Prices'=>__('Price')]),
			
		];
	}
	
	
}
