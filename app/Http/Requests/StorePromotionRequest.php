<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StorePromotionRequest extends FormRequest
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
	
		$model = $this->route('promotion') ;
		$HValidationRules = HValidation::rules('promotions', $model , Request::isMethod('post') , ['name']);
        return 
		[
			'name_en'=>$HValidationRules['name_en'],
			'name_ar'=>$HValidationRules['name_ar'],
			'start_date'=>$HValidationRules['start_date'],
			'end_date'=>$HValidationRules['end_date'],
			'discount_type'=>$HValidationRules['discount_type'],
			'amount'=>$HValidationRules['amount_or_percentage'],
			// 'is_active'=>$HValidationRules['is_active'],
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
			'start_date.required'=>__('Please Enter :attribute' , ['attribute'=>__('Start Date')]),
			'start_date.date_format'=>__('Invalid :attribute' , ['attribute'=>__('Start Date')]),
			'start_date.date'=>__('Invalid :attribute' , ['attribute'=>__('Start Date')]),
			
			'end_date.required'=>__('Please Enter :attribute' , ['attribute'=>__('End Date')]),
			'end_date.date_format'=>__('Invalid :attribute' , ['attribute'=>__('End Date')]),
			'end_date.date'=>__('Invalid :attribute' , ['attribute'=>__('End Date')]),
			'end_date.after'=>__(':attr Must Be After :attribute' , ['attr'=>__('End Date'),'attribute'=>__('Start Date')]),
			
			
		];
	}
	
	
}
