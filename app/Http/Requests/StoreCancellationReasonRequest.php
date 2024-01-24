<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreCancellationReasonRequest extends FormRequest
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
		$model = $this->route('cancellation_reason') ;
		$HValidationRules = HValidation::rules('cancellation_reasons', $model , Request::isMethod('post') , ['name']);
        return 
		[
			'name_en'=>$HValidationRules['name_en'],
			'name_ar'=>$HValidationRules['name_ar'],
			'model_type'=>'required',
			'is_active'=>$HValidationRules['is_active'],
        ];
    }
	public function messages()
	{
		return [
			'name_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English Name')]),
			'model_type.required'=>__('Please Enter :attribute' , ['attribute'=>__('Related To')]),
			'name_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English Name'),'max'=>255	]),
			'name_en.unique'=> __(':attribute Already Exist',['attribute'=>__('English Name')]),
			'name_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic Name')]),
			'name_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic Name'),'max'=>255	]),
			'name_ar.unique'=> __(':attribute Already Exist',['attribute'=>__('Arabic Name')]),
		];
	}
	
	
}
