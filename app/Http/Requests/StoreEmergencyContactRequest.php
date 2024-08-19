<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmergencyContactRequest extends FormRequest
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
		$addEmergencyContactFromExisting = Request()->has('from_existing_contact');
		if($addEmergencyContactFromExisting){
			/**
			 * * to add from existing contact in driver page or client
			 */
			return [];
		}
		$model = $this->route('emergency_contact') ;
		$HValidationRules = HValidation::rules('emergency_contacts', $model , Request()->isMethod('POST') , ['name']);
        return 
		[
			'name'=>$HValidationRules['first_name'],
			'email'=>$HValidationRules['email'],
			'country_id'=>$HValidationRules['country_id'],
			'phone'=>$HValidationRules['phone'],
			'can_receive_travel_info'=>$HValidationRules['can_receive_travel_info'],
        ];
    }
	public function messages()
	{
		return [
			'name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Name')]),
			// 'name.string'=> __(':attribute Must Be String',['attribute'=>__('Name')]),
			'name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Name'),'max'=>255	]),
			'name.unique'=> __(':attribute Already Exist',['attribute'=>__('Name')]),
			'country_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Country Name')]),
			
			'phone.required'=>__('Please Enter :attribute' , ['attribute'=>__('Phone')]),
			'phone.unique'=> __(':attribute Already Exist',['attribute'=>__('Phone')]),
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email')]),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email'),'max'=>255	]),
			'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email')]),
			
		];
	}
	
	
}
