<?php

namespace App\Http\Requests\Apis;

use App\Helpers\HHelpers;
use App\Rules\PhoneExistRule;
use App\Rules\VerificationCodeExistForPhoneRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
		$tableName = HHelpers::getTableNameFromRequest();
		$phone = Request('phone') ;
		$countryIso2 = Request('country_iso2');
        return
        [
            'country_code' => ['required', 'exists:countries,iso2'],
            'phone'=>['required',new PhoneExistRule($countryIso2 , $phone)],
			// 'verification_code'=>['required_without:send_verification_code|digits:4',new VerificationCodeExistForPhoneRule($tableName,$phone)],
			// 'resend_verification_code'=>'sometimes', // 1 or zero to resend verification code
			// 'send_verification_code_by'=>'required_with:resend_verification_code|in:sms,whatsapp'
        ];
    }

    public function messages()
    {
        return [
            'country_code.required' => __('Please Enter :attribute', ['attribute' => __('Country Code',[],getApiLang())],getApiLang()),
            'country_code.exists' => __(':attribute Not Exist', ['attribute' => __('Country Code',[],getApiLang())],getApiLang()),
            'phone.required' => __('Please Enter :attribute', ['attribute' => __('Phone',[],getApiLang())],getApiLang()),
			'verification_code.required_without'=>__('Please Enter Verification Code',[],getApiLang()),
			'verification_code.digits'=>__('Please Enter Valid Verification Code',[],getApiLang()),
        ];
    }
}
