<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendEmailMessageRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'receiver_name'=>['required'],
            'subject'=>['required'],
            'message'=>['required']
        ];
    }

    public function messages()
    {
        return [
            'receiver_name.required' => __('Please Enter :attribute', ['attribute' => __('Receiver Name',[],getApiLang())],getApiLang()),
            'email.required' => __('Please Enter :attribute', ['attribute' => __('Email',[],getApiLang())],getApiLang()),
            'subject.required' => __('Please Enter :attribute', ['attribute' => __('Message Subject',[],getApiLang())],getApiLang()),
            'message.required' => __('Please Enter :attribute', ['attribute' => __('Message',[],getApiLang())],getApiLang()),
        ];
    }
}
