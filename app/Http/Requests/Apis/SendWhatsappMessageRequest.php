<?php

namespace App\Http\Requests\Apis;

use App\Rules\PhoneExistRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendWhatsappMessageRequest extends FormRequest
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
            'country_code' => ['required', 'exists:countries,phonecode'],
            'phone'=>['required',new PhoneExistRule(Request('country_code') , Request('phone'))],
            'message'=>['required']
        ];
    }

    public function messages()
    {
        return [
            'country_code.required' => __('Please Enter :attribute', ['attribute' => __('Country Code',[],getApiLang())],getApiLang()),
            'country_code.exists' => __(':attribute Not Exist', ['attribute' => __('Country Code',[],getApiLang())],getApiLang()),
            'phone.required' => __('Please Enter :attribute', ['attribute' => __('Phone',[],getApiLang())],getApiLang()),
            'message.required' => __('Please Enter :attribute', ['attribute' => __('Message',[],getApiLang())],getApiLang()),
        ];
    }
}
