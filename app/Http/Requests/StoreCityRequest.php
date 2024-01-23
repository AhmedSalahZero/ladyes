<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreCityRequest extends FormRequest
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
		$model = $this->route('city') ;
		$HValidationRules = HValidation::rules('cities', $model , Request::isMethod('post'));
        return 
		[
			'name_en'=>$HValidationRules['name_en'],
			'name_ar'=>$HValidationRules['name_ar'],
			'latitude'=>$HValidationRules['latitude'],
			'longitude'=>$HValidationRules['longitude'],
			'country_id'=>$HValidationRules['country_id'],
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
			'country_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Country Name')]),
			'longitude.required'=>__('Please Enter :attribute' , ['attribute'=>__('Longitude')]),
			'longitude.between'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
			'latitude.required'=>__('Please Enter :attribute' , ['attribute'=>__('Latitude')]),
			'latitude.between'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
		];
	}
	
	
}
