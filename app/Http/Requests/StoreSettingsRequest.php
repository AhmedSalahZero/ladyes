<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreSettingsRequest extends FormRequest
{
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
		$model = $this->route('setting') ;
		$HValidationRules = HValidation::rules('settings', $model , Request::isMethod('post') );
		// dd($HValidationRules['phone']);
        return 
		[
			'app_name_en'=>$HValidationRules['app_name_en'],
			'app_name_ar'=>$HValidationRules['app_name_ar'],
			'app_logo'=>$HValidationRules['app_logo'],
			'fav_icon'=>$HValidationRules['fav_icon'],
			'facebook_url'=>$HValidationRules['facebook_url'],
			'instagram_url'=>$HValidationRules['instagram_url'],
			'twitter_url'=>$HValidationRules['twitter_url'],
			'youtube_url'=>$HValidationRules['youtube_url'],
			'deduction_percentage'=>$HValidationRules['deduction_percentage'],
			'driving_range'=>$HValidationRules['driving_range'],
			
			
			'phone'=>$HValidationRules['app_phone'],
			'email'=>$HValidationRules['app_email'],
		
        ];
    }
	public function messages()
	{
		return [
			'app_name_en.required'=>__('Please Enter :attribute' , ['attribute'=>__('English App Name')]),
			'app_name_en.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('English App Name'),'max'=>255	]),
			
			'app_name_ar.required'=>__('Please Enter :attribute' , ['attribute'=>__('Arabic App Name')]),
			'app_name_ar.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Arabic App Name'),'max'=>255	]),
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email')]),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email'),'max'=>255	]),
			// 'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email')]),
			
			'fav_icon.required'=>__('Please Enter :attribute' , ['attribute'=>__('Favicon')]),
			'fav_icon.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Favicon')]),
			'fav_icon.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Favicon')]),
			
			
			'app_logo.required'=>__('Please Enter :attribute' , ['attribute'=>__('Logo')]),
			'app_logo.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Logo')]),
			'app_logo.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Logo')]),
			
			
			'facebook_url.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Facebook URL'),'max'=>255	]),
			'facebook_url.url'=> __(':attribute Invalid URL',['attribute'=>__('Facebook URL')]),
			'instagram_url.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Instagram URL'),'max'=>255	]),
			'instagram_url.url'=> __(':attribute Invalid URL',['attribute'=>__('Instagram URL')	]),
			'twitter_url.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Twitter URL'),'max'=>255	]),
			'twitter_url.url'=> __(':attribute Invalid URL',['attribute'=>__('Twitter URL')]),
			'youtube_url.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Youtube URL'),'max'=>255	]),
			'youtube_url.url'=> __(':attribute Invalid URL',['attribute'=>__('Youtube URL')	]),
			
			
			'deduction_percentage.number'=>__('Please Enter Valid :attribute' , ['attribute'=>__('Deduction Percentage')]),
			'deduction_percentage.gte'=>__('Only Greater Than Or Equal Zero Allowed For :attribute' , ['attribute'=>__('Deduction Percentage')]),
			
			
			'WHATSAPP_AUTH_KEY.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Whatsapp Auth Key'),'max'=>255	]),
			'WHATSAPP_APP_KEY.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Whatsapp App Key'),'max'=>255	]),
		];
	}
	
	
}
