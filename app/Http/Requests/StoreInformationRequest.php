<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreInformationRequest extends FormRequest
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
		$model = $this->route('information') ;
		$HValidationRules = HValidation::rules('information', $model , Request::isMethod('post') );
        return 
		[
			'name_en'=>$HValidationRules['name_en'],
			'name_ar'=>$HValidationRules['name_ar'],
			'description_en'=>$HValidationRules['description_en'],
			'description_ar'=>$HValidationRules['description_ar'],
			'section_name'=>'required',
			'is_active'=>$HValidationRules['is_active'],
        ];
    }
	public function messages()
	{
		return [
			
			'name_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English Name')]),
			'name_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English Name'),'max'=>255	]),
			'name_en.unique'=> __(':attribute Already Exist',['attribute'=>__('English Name')]),
			'name_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic Name')]),
			'name_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic Name'),'max'=>255	]),
			'name_ar.unique'=> __(':attribute Already Exist',['attribute'=>__('Arabic Name')]),
			
			'description_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English Description')]),
			'description_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English Description'),'max'=>500	]),
			
			
			'description_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic Description')]),
			'description_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic Description'),'max'=>500	]),
			
			
			
			'section_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Section Name')]),
		];
	}
	
	
}
