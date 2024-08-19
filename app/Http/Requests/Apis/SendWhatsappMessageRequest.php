<?php

namespace App\Http\Requests\Apis;

use App\Models\Country;
use App\Rules\ValidPhoneNumberRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendWhatsappMessageRequest extends FormRequest
{
    use HasFailedValidation;
    protected $stopOnFirstFailure = true;

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
        $countryIso2 = Request('country_iso2');
        $country = Country::findByIso2($countryIso2);

        return
        [
            'country_iso2' => ['bail', 'required', 'exists:countries,iso2'],
			'user_type'=>'required|in:Driver,Client',
            'phone' => ['required', new ValidPhoneNumberRule($country ? $country->id : 0)],
        ];
    }

    public function messages()
    {
        return [
            'country_iso2.required' => __('Please Enter :attribute', ['attribute' => __('Country ISO2', [], getApiLang())], getApiLang()),
            'country_iso2.exists' => __(':attribute Not Exist', ['attribute' => __('Country ISO2', [], getApiLang())], getApiLang()),
            'user_type.required' => __('Please Enter :attribute', ['attribute' => __('User Type', [], getApiLang())], getApiLang()),
            'user_type.in' => __('Invalid :attribute', ['attribute' => __('User Type', [], getApiLang())]),
            'phone.required' => __('Please Enter :attribute', ['attribute' => __('Phone', [], getApiLang())], getApiLang()),
            'phone.numeric' => __('Invalid :attribute', ['attribute' => __('Phone', [], getApiLang())]),
        ];
    }
}
