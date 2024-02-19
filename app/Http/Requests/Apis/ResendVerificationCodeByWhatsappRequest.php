<?php

namespace App\Http\Requests\Apis;

use App\Rules\ExistByModelTypeRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationCodeByWhatsappRequest extends FormRequest
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
            'model_type' => ['required', 'in:Driver,Client'],
			'model_id'=>['required',new ExistByModelTypeRule(Request('model_type'))],
        ];
    }

    public function messages()
    {
        return [
            'model_type.required' => __('Please Enter :attribute', ['attribute' => __('Model Type',[],getApiLang())],getApiLang()),
            'model_type.in' => __('Only Allowed Value [Driver,Client]', ['attribute' => __('Model Type',[],getApiLang())],getApiLang()),
            'model_id.required' => __('Please Enter :attribute', ['attribute' => __('Model Id',[],getApiLang())],getApiLang()),
            'phone.required' => __('Please Enter :attribute', ['attribute' => __('Phone',[],getApiLang())],getApiLang()),
            'message.required' => __('Please Enter :attribute', ['attribute' => __('Message',[],getApiLang())],getApiLang()),
        ];
    }
}
