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
			'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
			'country_id'=>$HValidationRules['country_id'],
			'rush_hours'=>'required|array',
			'rush_hours.*.start_time'=>'required',
			'rush_hours.*.end_time'=>'required',
			'rush_hours.*.end_time'=>'required',
			// 'rush_hours.*.price'=>$HValidationRules['price'],
			'rush_hours.*.km_price'=>$HValidationRules['price'],
			'rush_hours.*.minute_price'=>$HValidationRules['price'],
			'rush_hours.*.cancellation_fees_for_client'=>$HValidationRules['price'],
			'rush_hours.*.late_fees_for_client'=>$HValidationRules['price'],
			'rush_hours.*.cash_fees'=>$HValidationRules['price'],
			'rush_hours.*.first_travel_bonus'=>$HValidationRules['price'],
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
			'price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Price')]),
			
			'km_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Km Price')]),
			'km_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Km Price')]),
			'km_price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Km Price')]),
			
			
			
			'minute_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Minute Price')]),
			'minute_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Minute Price')]),
			'minute_price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Minute Price')]),
			
			
			'operating_fees.required'=>__('Please Enter :attribute' , ['attribute'=>__('Operating Fees')]),
			'operating_fees.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Operating Fees')]),
			'operating_fees.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Operating Fees')]),
			
			'rush_hours.*.start_time'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Start Time')]),
			'rush_hours.*.end_time'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour End Time')]),
			
			
			'rush_hours.*.price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Price')]),
			'rush_hours.*.price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Price')]),
			'rush_hours.*.price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Price')]),
			
			
			'rush_hours.*.km_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			'rush_hours.*.km_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			'rush_hours.*.km_price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Km Price')]),
			
			
			'rush_hours.*.minute_price.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			'rush_hours.*.minute_price.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			'rush_hours.*.minute_price.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Minute Price')]),
			
			'rush_hours.*.cancellation_fees_for_client.required'=>__('Please Enter :attribute' , ['attribute'=>__('Cancellation Fees For Client')]),
			'rush_hours.*.cancellation_fees_for_client.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Cancellation Fees For Client')]),
			'rush_hours.*.cancellation_fees_for_client.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Cancellation Fees For Client')]),			
			
			'rush_hours.*.late_fees_for_client.required'=>__('Please Enter :attribute' , ['attribute'=>__('Late Fees For Client')]),
			'rush_hours.*.late_fees_for_client.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Late Fees For Client')]),
			'rush_hours.*.late_fees_for_client.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Late Fees For Client')]),			
			
			
			'rush_hours.*.cash_fees.required'=>__('Please Enter :attribute' , ['attribute'=>__('Cash Fees')]),
			'rush_hours.*.cash_fees.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Cash Fees')]),
			'rush_hours.*.cash_fees.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Cash Fees')]),		
			
			
			'rush_hours.*.first_travel_bonus.required'=>__('Please Enter :attribute' , ['attribute'=>__('Bonus After First Success Travel')]),
			'rush_hours.*.first_travel_bonus.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Bonus After First Success Travel')]),
			'rush_hours.*.first_travel_bonus.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Bonus After First Success Travel')]),
			
			
			'rush_hours.*.operating_fees.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			'rush_hours.*.operating_fees.numeric'=>__('Invalid :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			'rush_hours.*.operating_fees.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			
			'rush_hours.*.percentage.required'=>__('Please Enter :attribute' , ['attribute'=>__('Rush Hour Operating Fees')]),
			
			
			'latitude.required' => __('Please Enter :attribute', ['attribute' => __('Latitude',[],getApiLang())],getApiLang()),
			'latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'longitude.required' => __('Please Enter :attribute', ['attribute' => __('Longitude',[],getApiLang())],getApiLang()),
			'longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
			
		];
	}
	
	
}
