<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ShowCarModelRequest extends FormRequest
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
            'make_id' => ['required', 'exists:car_makes,id'],
        ];
    }

    public function messages()
    {
        return [
            'make_id.required' => __('Please Enter :attribute', ['attribute' => __('Make Id',[],getApiLang())],getApiLang()),
            'make_id.exists' => __(':attribute Not Exist', ['attribute' => __('Make Id',[],getApiLang())],getApiLang()),
        ];
    }
}
