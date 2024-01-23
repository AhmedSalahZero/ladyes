<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreAdminRequest extends FormRequest
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
		$model = $this->route('admin') ;
		$HValidationRules = HValidation::rules('admins', $model , Request::isMethod('post') , ['name']);
        return 
		[
			'name'=>$HValidationRules['name'],
			'email'=>$HValidationRules['email'],
			'password'=>$HValidationRules['password'],
			'role_name'=>$HValidationRules['role_name'],
			'is_active'=>$HValidationRules['is_active'],
        ];
    }
	public function messages()
	{
		return [
			'name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Name')]),
			// 'name.string'=> __(':attribute Must Be String',['attribute'=>__('Name')]),
			'name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Name'),'max'=>255	]),
			'name.unique'=> __(':attribute Already Exist',['attribute'=>__('Name')]),
			
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email')]),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email'),'max'=>255	]),
			'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email')]),
			
			
			'password.required'=>__('Please Enter :attribute' , ['attribute'=>__('Password')]),
			'password.min'=> __(':attribute Must Be At Least :min Letter',['attribute'=>__('Password'),'min'=>8	]),
			
			'role_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Role Name')]),
			
		];
	}
	
	
}
