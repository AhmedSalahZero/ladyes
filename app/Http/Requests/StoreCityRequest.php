<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Rules\RushHourPercentageRule;
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
			// 'price'=>$HValidationRules['price'],
			// 'latitude'=>$HValidationRules['latitude'],
			// 'longitude'=>$HValidationRules['longitude'],
			'country_id'=>$HValidationRules['country_id'],
			'rush_hours'=>'required|array',
			'rush_hours.*.start_time'=>'required',
			'rush_hours.*.end_time'=>'required',
			'rush_hours.*.end_time'=>'required',
			// 'rush_hours.*.price'=>$HValidationRules['price'],
			'rush_hours.*.km_price'=>$HValidationRules['price'],
			'rush_hours.*.minute_price'=>$HValidationRules['price'],
			'rush_hours.*.operating_fees'=>$HValidationRules['price'],
			'rush_hours.*.percentage'=>['required',new RushHourPercentageRule],
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
			'price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Price')]),
			'price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Price')]),
			'price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Price')]),
			
			'km_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Km Price')]),
			'km_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Km Price')]),
			'km_price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Km Price')]),
			
			
			'minute_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Minute Price')]),
			'minute_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Minute Price')]),
			'minute_price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Minute Price')]),
			
			
			'operating_fees.required'=>__('Please Enter :attribute' , ['attribute'=>__('Operating Fees')]),
			'operating_fees.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Operating Fees')]),
			'operating_fees.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Operating Fees')]),
			
			'rush_hours.*.start_time'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Start Time')]),
			'rush_hours.*.end_time'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour End Time')]),
			
			
			'rush_hours.*.price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Price')]),
			'rush_hours.*.price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Price')]),
			'rush_hours.*.price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Price')]),
			
			
			'rush_hours.*.km_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			'rush_hours.*.km_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			'rush_hours.*.km_price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			
			
			'rush_hours.*.minute_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			'rush_hours.*.minute_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			'rush_hours.*.minute_price.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			
			
			'rush_hours.*.operating_fees.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			'rush_hours.*.operating_fees.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			'rush_hours.*.operating_fees.gte'=>__('Only Greater Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			
			'rush_hours.*.percentage.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
		];
	}
	
	
}
