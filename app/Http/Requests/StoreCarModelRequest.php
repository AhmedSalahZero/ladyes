<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreCarModelRequest extends FormRequest
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
		$model = $this->route('car_model') ;
		$HValidationRules = HValidation::rules('car_models', $model , Request::isMethod('post'));
        return 
		[
			'name_en'=>$HValidationRules['name_en'],
			'name_ar'=>$HValidationRules['name_ar'],
			// 'manufacturing_year'=>$HValidationRules['manufacturing_year'],
			'make_id'=>$HValidationRules['make_id'],
			'logo'=>$HValidationRules['logo']
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
			// 'manufacturing_year.required'=>__('Please Enter :attribute' , ['attribute'=>__('Manufacturing Year')]),
			// 'manufacturing_year.integer'=>__('Please Enter Valid :attribute' , ['attribute'=>__('Manufacturing Year')]),
			'make_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Car Make')]),
			'logo.required'=>__('Please Enter :attribute' , ['attribute'=>__('Logo')]),
			'logo.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Logo')]),
			'logo.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Logo')]),
			
			
		];
	}
	
	
}
