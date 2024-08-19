<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class MarkTravelAsCancelledRequest extends FormRequest
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
            'cancellation_reason_id' => ['sometimes','required', 'exists:cancellation_reasons,id'],
        ];
    }

    public function messages()
    {
        return [
            'cancellation_reason_id.required' => __('Please Enter :attribute', ['attribute' => __('Cancellation Reason Id',[],getApiLang())],getApiLang()),
            'cancellation_reason_id.exists' => __(':attribute Not Exist', ['attribute' => __('Cancellation Reason Id',[],getApiLang())],getApiLang()),
        ];
    }
}
