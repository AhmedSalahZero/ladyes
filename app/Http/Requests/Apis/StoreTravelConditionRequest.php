<?php

namespace App\Http\Requests\Apis;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTravelConditionRequest extends FormRequest
{ 
	protected $stopOnFirstFailure = true;
	
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
		return [
			'travel_condition_ids'=>'required|array',
			'travel_condition_ids.*'=>['required',Rule::exists('travel_conditions', 'id')],
		];
    }
	public function messages()
	{
		return [
			'travel_condition_ids.required'=>__('Please Enter :attribute' , ['attribute'=>__('Travel Condition Ids')]),
			'travel_condition_ids.array'=>__('Travel Condition Ids Must Be An Array',[],getApiLang()),
			'travel_condition_ids.*.required'=>__('Please Enter :attribute' , ['attribute'=>__('Travel Condition Ids')]),
			'travel_condition_ids.*.exists'=>__('Invalid :attribute' , ['attribute'=>__('Travel Condition Ids')]),
		];
	}
	
	
}
