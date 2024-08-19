<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class getDistanceBetweenTwoClientAndDriverRequest extends FormRequest
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
        return
        [
     
			'from_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'from_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
			'to_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'to_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
			
        ];
    }

    public function messages()
    {
        return [
            'from_latitude.required' => __('Please Enter :attribute', ['attribute' => __('Latitude',[],getApiLang())],getApiLang()),
			'from_latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'from_longitude.required' => __('Please Enter :attribute', ['attribute' => __('Longitude',[],getApiLang())],getApiLang()),
			'from_longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
			
			'to_latitude.required' => __('Please Enter :attribute', ['attribute' => __('Latitude',[],getApiLang())],getApiLang()),
			'to_latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'to_longitude.required' => __('Please Enter :attribute', ['attribute' => __('Longitude',[],getApiLang())],getApiLang()),
			'to_longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
			
        ];
    }
}
