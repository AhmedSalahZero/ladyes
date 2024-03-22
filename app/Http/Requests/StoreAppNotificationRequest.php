<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreAppNotificationRequest extends FormRequest
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
		$model = $this->route('notification') ;
		$HValidationRules = HValidation::rules('car_makes', $model , Request::isMethod('post'));
        return 
		[
			'title_en'=>$HValidationRules['title_en'],
			'title_ar'=>$HValidationRules['title_ar'],
			'message_en'=>$HValidationRules['message_en'],
			'message_ar'=>$HValidationRules['message_ar'],
			'client_ids'=>'required_without:driver_ids|exists:clients,id',
			'driver_ids'=>'required_without:client_ids|exists:drivers,id',
			'type'=>'required'
        ];
    }
	public function messages()
	{
		return [
			'title_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English Title')]),
			'title_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English Title'),'max'=>255	]),
			'title_en.unique'=> __(':attribute Already Exist',['attribute'=>__('English Title')]),
			
			'title_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic Title')]),
			'title_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic Title'),'max'=>255	]),
			'title_ar.unique'=> __(':attribute Already Exist',['attribute'=>__('Arabic Title')]),
			
			
			'message_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English Message')]),
			'message_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English Message'),'max'=>255	]),
			'message_en.unique'=> __(':attribute Already Exist',['attribute'=>__('English Message')]),
			
			'message_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic Message')]),
			'message_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic Message'),'max'=>255	]),
			'message_ar.unique'=> __(':attribute Already Exist',['attribute'=>__('Arabic Message')]),
			
			
			'client_ids.required_without'=>__('Please Enter At Least One Client Or Driver To Send Message'),
			'driver_ids.required_without'=>__('Please Enter At Least One Client Or Driver To Send Message'),
			'type.required'=>__('Please Select Notification Type')			
		];
	}
	
	
}
