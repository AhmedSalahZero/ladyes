<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class NumberOfTravelPerAreaRequest extends FormRequest
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
			'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
			'radius'=>'required|gt:0',
		];
    }

    public function messages()
    {
        return [
			'latitude.required' => __('Please Enter :attribute', ['attribute' => __('Latitude',[],getApiLang())],getApiLang()),
			'latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'longitude.required' => __('Please Enter :attribute', ['attribute' => __('Longitude',[],getApiLang())],getApiLang()),
			'longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude',[],getApiLang())]),
			'radius.required'=>__('Please Enter :attribute' , ['attribute'=>__('Radius',[],getApiLang())]),
			'radius.gt'=>__('Only Greater Than Zero Allowed For :attribute',['attribute'=>__('Radius',[],getApiLang())])
        ];
    }
}
