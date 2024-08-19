<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ShowTravelPerHourReportRequest extends FormRequest
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
            'report_type' => ['required', 'in:daily,weekly,monthly'],
        ];
    }

    public function messages()
    {
        return [
            'model_type.required' => __('Please Enter :attribute', ['attribute' => __('Report Type',[],getApiLang())],getApiLang()),
			'model_type.in' => __('Only Allowed Values (daily , weekly , monthly)', ['attribute' => __('Model Type',[],getApiLang())],getApiLang()),
            'date.required' => __('Please Enter :attribute', ['attribute' => __('Date',[],getApiLang())],getApiLang()),
			'date.date'=>__('Invalid :attribute',['attribute'=>__('Date',[],getApiLang())],getApiLang()),
			'date.date_format'=>__('Invalid :attribute',['attribute'=>__('Date',[],getApiLang())],getApiLang())
        ];
    }
}
