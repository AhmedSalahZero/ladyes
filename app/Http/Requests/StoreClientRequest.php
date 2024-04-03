<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreClientRequest extends FormRequest
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
		$model = $this->route('client')?:Request()->user('client') ;
		$countryIso2 = Request()->get('country_iso2');
		
		$HValidationRules = HValidation::rules('clients', $model , Request::isMethod('post') );
	
        return 
		[
			'first_name'=>$HValidationRules['first_name'],
			'last_name'=>$HValidationRules['last_name'],
			'country_iso2'=>'required|exists:countries,iso2',
			// 'country_id'=>$HValidationRules['country_id'],
			// 'city_id'=>$HValidationRules['city_id'],
			'email'=>$HValidationRules['email'],
			'phone'=>$HValidationRules['phone'],
			'image'=>$HValidationRules['client_image'],
        ];
    }
	public function messages()
	{
		return [
			'first_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('First Name')]),
			'first_name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('First Name'),'max'=>255	]),
			
			
			
			'last_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Last Name')]),
			'last_name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Last Name'),'max'=>255	]),
			
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email')]),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email'),'max'=>255	]),
			'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email')]),
			
			
			'phone.required'=>__('Please Enter :attribute' , ['attribute'=>__('Phone')]),
			'phone.unique'=> __(':attribute Already Exist',['attribute'=>__('Phone')]),
			
			'country_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Country Name')]),
			'city_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('City Name')]),
			'image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Image')]),
			'image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Image')]),
			'image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Image')]),
			
			'country_iso2.required' => __('Please Enter :attribute', ['attribute' => __('Country ISO2',[],getApiLang())],getApiLang()),
            'country_iso2.exists' => __(':attribute Not Exist', ['attribute' => __('Country ISO2',[],getApiLang())],getApiLang()),
          
			
		];
	}
	
	
}
