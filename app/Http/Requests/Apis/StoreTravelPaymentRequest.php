<?php

namespace App\Http\Requests\Apis;

use App\Enum\PaymentType;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelPaymentRequest extends FormRequest
{ 
	protected $stopOnFirstFailure = true;
	
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
			'payment_method'=>['sometimes','required','in:'.implode(',',array_keys(PaymentType::all()))],
        ];
    }
	public function messages()
	{
		return [
			'travel_id.required' => __('Please Enter :attribute', ['attribute' => __('Travel Id',[],getApiLang())],getApiLang()),
            'travel_id.exists' => __(':attribute Not Exist', ['attribute' => __('Travel Id',[],getApiLang())],getApiLang()),
			'payment_method.required'=>__('Please Enter :attribute' , ['attribute'=>__('Payment Type',[],getApiLang())]),
			'payment_method.in'=>__('Invalid :attribute' , ['attribute'=>__('Payment Type')],getApiLang()),
			'currency.required'=>__('Please Enter :attribute' , ['attribute'=>__('Currency',[],getApiLang())]),
            'currency.exists' => __(':attribute Not Exist', ['attribute' => __('The Currency',[],getApiLang())],getApiLang()),
		];
	}
	
	
}
