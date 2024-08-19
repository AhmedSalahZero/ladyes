<?php

namespace App\Http\Requests\Apis;
use App\Helpers\HValidation;
use App\Rules\UniqueToClientRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
		
		$model = $this->route('address') ;
		$requiredOrNot = $model ? 'sometimes' : 'required';
		$id = $model ? $model->id : 0 ;
        return 
		[
			'category'=>[$requiredOrNot,'required','max:255',new UniqueToClientRule('Address',__('This Address Category Already Exist',[],getApiLang()),$id)],
			'description'=>[$requiredOrNot,'required','max:255'],
			'latitude'=>[$requiredOrNot,'required'],
			'longitude'=>[$requiredOrNot,'required'],
        ];
    }
	public function messages()
	{
		return [
			'category.required'=>__('Please Enter :attribute' , ['attribute'=>__('Address Category',[],getApiLang())]),
			'category.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Address Category'),'max'=>255	]),

			'description.required'=>__('Please Enter :attribute' , ['attribute'=>__('Address Description',[],getApiLang())]),
			'description.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Address Description'),'max'=>255	]),
			
			'latitude.required'=>__('Please Enter :attribute' , ['attribute'=>__('Latitude',[],getApiLang())]),
			'longitude.required'=>__('Please Enter :attribute' , ['attribute'=>__('Longitude',[],getApiLang())]),
		];
	}
	
	
}
