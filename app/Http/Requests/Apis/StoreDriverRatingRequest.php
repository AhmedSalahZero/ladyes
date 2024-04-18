<?php

namespace App\Http\Requests\Apis;

use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRatingRequest extends FormRequest
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
            'driver_id' => ['required', 'exists:drivers,id'],
			'rate'=>['required','gte:0','lt:6'],
			'comment'=>'sometimes|required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'driver_id.required' => __('Please Enter :attribute', ['attribute' => __('Driver Id',[],getApiLang())],getApiLang()),
            'driver_id.exists' => __(':attribute Not Exist', ['attribute' => __('Driver Id',[],getApiLang())],getApiLang()),
            'rate.required' => __('Please Enter :attribute', ['attribute' => __('Rating No',[],getApiLang())],getApiLang()),
			'rate.gte'=>__('Rating Stars Must Be At Least Zero',[],getApiLang()),
			'rate.lt'=>__('Rating Stars Can Not Be Greater Than Five',[],getApiLang()),
			
			'comment.required'=>__('Please Enter :attribute' , ['attribute'=>__('Comment',[],getApiLang())]),
			'comment.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Comment'),'max'=>255	]),
			
        ];
    }
}
