<?php

namespace App\Http\Requests\Apis;
use App\Helpers\HValidation;
use App\Models\EmergencyContact;
use App\Rules\ValidPhoneNumberRule;
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
		$clientOrDriver = Request()->user('client')?:Request()->user('driver');
		$model = $clientOrDriver->emergencyContacts->first() ?:  new EmergencyContact();
		$HValidationRules = HValidation::rules('emergency_contacts', $model , Request()->isMethod('POST') , ['name']);
        return 
		[
			'name'=>$HValidationRules['first_name'],
			'email'=>['required','max:255','unique:emergency_contacts,email'.','.$model->id ],
			'country_id'=>$HValidationRules['country_id'],
			'phone'=>['required','unique:emergency_contacts,phone'.','.$model->id,new ValidPhoneNumberRule(Request()->get('country_id',Request()->get('country_iso2')))],
			'can_receive_travel_info'=>$HValidationRules['can_receive_travel_info'],
        ];
    }
	public function messages()
	{
		return [
			'name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Name',[],getApiLang())],getApiLang()),
			// 'name.string'=> __(':attribute Must Be String',['attribute'=>__('Name')]),
			'name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Name',[],getApiLang()),'max'=>255	],getApiLang()),
			'name.unique'=> __(':attribute Already Exist',['attribute'=>__('Name',[],getApiLang())],getApiLang()),
			'country_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Country Name',[],getApiLang())],getApiLang()),
			
			'phone.required'=>__('Please Enter :attribute' , ['attribute'=>__('Phone',[],getApiLang())],getApiLang()),
			'phone.unique'=> __(':attribute Already Exist',['attribute'=>__('Phone',[],getApiLang())],getApiLang()),
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email',[],getApiLang())],getApiLang()),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email',[],getApiLang()),'max'=>255	],getApiLang()),
			'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email',[],getApiLang())])	
		];
	}
	
	
}
