<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRatingRequest extends FormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
			'rate'=>['required','gte:0','lt:6']
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => __('Please Enter :attribute', ['attribute' => __('Client Id',[],getApiLang())],getApiLang()),
            'client_id.exists' => __(':attribute Not Exist', ['attribute' => __('Client Id',[],getApiLang())],getApiLang()),
            'rate.required' => __('Please Enter :attribute', ['attribute' => __('Rating No',[],getApiLang())],getApiLang()),
			'rate.gte'=>__('Rating Stars Must Be At Least Zero',[],getApiLang()),
			'rate.lt'=>__('Rating Stars Can Not Be Greater Than Five',[],getApiLang()),
			
        ];
    }
}
