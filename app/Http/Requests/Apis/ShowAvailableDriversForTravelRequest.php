<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ShowAvailableDriversForTravelRequest extends FormRequest
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
            'car_size_id' => ['required', 'exists:car_sizes,id'],
			'client_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'client_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }

    public function messages()
    {
        return [
            'car_size_id.required' => __('Please Enter :attribute', ['attribute' => __('Car Size Id',[],getApiLang())],getApiLang()),
            'car_size_id.exists' => __(':attribute Not Exist', ['attribute' => __('Car Size Id',[],getApiLang())],getApiLang()),
            'client_latitude.required' => __('Please Enter :attribute', ['attribute' => __('Client Latitude',[],getApiLang())],getApiLang()),
			'client_latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'client_longitude.required' => __('Please Enter :attribute', ['attribute' => __('Client Longitude',[],getApiLang())],getApiLang()),
			'client_longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
        ];
    }
}
