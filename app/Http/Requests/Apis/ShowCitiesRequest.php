<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ShowCitiesRequest extends FormRequest
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
            'country_id' => ['required', 'exists:countries,id'],
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => __('Please Enter :attribute', ['attribute' => __('Country Id',[],getApiLang())],getApiLang()),
            'country_id.exists' => __(':attribute Not Exist', ['attribute' => __('Country Id',[],getApiLang())],getApiLang()),
        ];
    }
}
