<?php

namespace App\Http\Requests\Apis;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupportTicketRequest extends FormRequest
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
		return [
			'subject'=>'required|max:255',
			'message'=>'required|max:255',
		];
    }
	public function messages()
	{
		return [
			'subject.required'=>__('Please Enter :attribute' , ['attribute'=>__('Subject',[],getApiLang())],getApiLang()),
			'subject.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Subject',[],getApiLang()),'max'=>255	],getApiLang()),
			'message.required'=>__('Please Enter :attribute' , ['attribute'=>__('Message',[],getApiLang())],getApiLang()),
			'message.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Message',[],getApiLang()),'max'=>255	],getApiLang()),
			
		];
	}
	
	
}
