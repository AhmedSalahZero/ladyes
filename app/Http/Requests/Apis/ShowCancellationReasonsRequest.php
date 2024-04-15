<?php

namespace App\Http\Requests\Apis;

use App\Enum\CancellationReasonPhases;
use App\Helpers\HHelpers;
use App\Rules\PhoneExistRule;
use App\Rules\VerificationCodeExistForPhoneRule;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ShowCancellationReasonsRequest extends FormRequest
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
		/**
		 * * sometimes 
		 * * علشان في حالة السواق هتكون فاضيه
		 */
        return
        [
			'phase'=>['sometimes','required','in:'.implode(',',array_keys(CancellationReasonPhases::all()))],
        ];
    }

    public function messages()
    {
        return [
			'phase.required'=>__('Please Enter :attribute' , ['attribute'=>__('Phase',[],getApiLang())]),
			'phase.in'=>__('Invalid :attribute' , ['attribute'=>__('Phase')],getApiLang()),
	
        ];
    }
}
