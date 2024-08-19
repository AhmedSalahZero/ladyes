<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreRoleRequest extends FormRequest
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
		$model = $this->route('role') ;
		$HValidationRules = HValidation::rules('roles', $model , Request::isMethod('post') , ['name']);
        return 
		[
			'name'=>$HValidationRules['name'],
        ];
    }
	
	
}
