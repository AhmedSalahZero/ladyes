<?php

namespace App\Http\Requests\Apis;

use App\Models\Country;
use App\Rules\UserIsActiveAndIfExistRule;
use App\Rules\ValidPhoneNumberRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendVerificationCodeRequest extends FormRequest
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
		$phone = Request('phone') ;
		$countryIso2 = Request('country_iso2');
		$country = Country::findByIso2($countryIso2);
		
        return
        [
            'country_iso2' => ['bail','required', 'exists:countries,iso2'],
            'phone'=>['required',new ValidPhoneNumberRule($country ? $country->id : 0),new UserIsActiveAndIfExistRule($countryIso2,$phone)],
        ];
    }

    public function messages()
    {
        return [
            'country_iso2.required' => __('Please Enter :attribute', ['attribute' => __('Country ISO2',[],getApiLang())],getApiLang()),
            'country_iso2.exists' => __(':attribute Not Exist', ['attribute' => __('Country ISO2',[],getApiLang())],getApiLang()),
            'phone.required' => __('Please Enter :attribute', ['attribute' => __('Phone',[],getApiLang())],getApiLang()),
            'phone.numeric' => __('Invalid :attribute' , ['attribute'=>__('Phone',[],getApiLang())]),
			
        ];
    }
}
