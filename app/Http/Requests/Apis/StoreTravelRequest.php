<?php

namespace App\Http\Requests\Apis;

use App\Enum\PaymentType;
use App\Helpers\HValidation;
use App\Rules\UniqueToClientRule;
use App\Rules\ValidCouponRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelRequest extends FormRequest
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
		
		$model = $this->route('travel') ;
		$requiredOrNot = $model ? 'sometimes' : 'required';
		$id = $model ? $model->id : 0 ;
        return 
		[
			'client_id' => ['required', 'exists:clients,id'],
			'driver_id' => ['required', 'exists:drivers,id'],
			'country_id'=>['required', 'exists:countries,id'],
			'city_id'=>['required', 'exists:cities,id'],
			'from_latitude'=>[$requiredOrNot,'required'],
			'to_latitude'=>[$requiredOrNot,'required'],
			'from_longitude'=>[$requiredOrNot,'required'],
			'to_longitude'=>[$requiredOrNot,'required'],
			'address'=>[$requiredOrNot,'required','max:255'],
			'coupon_code'=>['sometimes','required',new ValidCouponRule()],
			'is_secure'=>['sometimes','required','in:1,0'],
			// 'currency' => ['required', 'exists:countries,currency'],
			// 'payment_type'=>['required','in:'.implode(',',array_keys(PaymentType::all()))],
        ];
    }
	public function messages()
	{
		return [
			'client_id.required' => __('Please Enter :attribute', ['attribute' => __('Client Id',[],getApiLang())],getApiLang()),
            'client_id.exists' => __(':attribute Not Exist', ['attribute' => __('Client Id',[],getApiLang())],getApiLang()),
			
			// 'country_id.required' => __('Please Enter :attribute', ['attribute' => __('Country Id',[],getApiLang())],getApiLang()),
            // 'country_id.exists' => __(':attribute Not Exist', ['attribute' => __('Country Id',[],getApiLang())],getApiLang()),
			
			'city_id.required' => __('Please Enter :attribute', ['attribute' => __('City Id',[],getApiLang())],getApiLang()),
            'city_id.exists' => __(':attribute Not Exist', ['attribute' => __('City Id',[],getApiLang())],getApiLang()),
			
			'coupon_code.required' => __('Please Enter :attribute', ['attribute' => __('Coupon Code',[],getApiLang())],getApiLang()),
            // 'coupon_code.exists' => __(':attribute Not Exist', ['attribute' => __('Coupon Id',[],getApiLang())],getApiLang()),
			
			
			'driver_id.required' => __('Please Enter :attribute', ['attribute' => __('Driver Id',[],getApiLang())],getApiLang()),
            'driver_id.exists' => __(':attribute Not Exist', ['attribute' => __('Driver Id',[],getApiLang())],getApiLang()),
			
			'address.required'=>__('Please Enter :attribute' , ['attribute'=>__('Address',[],getApiLang())]),
			'address.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Address'),'max'=>255	]),
			
			
			'from_latitude.required'=>__('Please Enter From Latitude',[],getApiLang() ),
			'from_longitude.required'=>__('Please Enter From Longitude',[],getApiLang() ),
			'to_latitude.required'=>__('Please Enter To Latitude',[],getApiLang() ),
			'to_longitude.required'=>__('Please Enter To Longitude',[],getApiLang() ),
			'is_secure.required'=>__('Please Specific If Travel Is Secure Or Not',[],getApiLang())	,		
			'is_secure.in'=>__('Invalid :attribute' , ['attribute'=>__('Is Secure')],getApiLang()),
			
			'payment_type.required'=>__('Please Enter :attribute' , ['attribute'=>__('Payment Type',[],getApiLang())]),
			'payment_type.in'=>__('Invalid :attribute' , ['attribute'=>__('Payment Type')],getApiLang()),
			'currency.required'=>__('Please Enter :attribute' , ['attribute'=>__('Currency',[],getApiLang())]),
            'currency.exists' => __(':attribute Not Exist', ['attribute' => __('The Currency',[],getApiLang())],getApiLang()),
		];
	}
	
	
}
