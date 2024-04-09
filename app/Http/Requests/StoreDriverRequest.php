<?php

namespace App\Http\Requests;
use App\Helpers\HValidation;
use App\Traits\HasFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreDriverRequest extends FormRequest
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
		$model = $this->route('driver') ?: Request()->user('driver') ;
		$HValidationRules = HValidation::rules('drivers', $model , Request::isMethod('post') );
        return 
		[
			'first_name'=>$HValidationRules['first_name'],
			'last_name'=>$HValidationRules['last_name'],
			'birth_date'=>$HValidationRules['birth_date'],
			'country_id'=>$HValidationRules['country_id'],
			'city_id'=>$HValidationRules['city_id'],
			'email'=>$HValidationRules['email'],
			'phone'=>$HValidationRules['phone'],
			'id_number'=>$HValidationRules['id_number'],
			'deduction_percentage'=>$HValidationRules['deduction_percentage'],
			'driving_range'=>$HValidationRules['driving_range'],
			'make_id'=>$HValidationRules['make_id'],
			'model_id'=>$HValidationRules['model_id'],
			'size_id'=>$HValidationRules['size_id'],
			'image'=>$HValidationRules['image'],
			'id_number_image'=>$HValidationRules['id_number_image'],
			'insurance_image'=>$HValidationRules['insurance_image'],
			'driver_license_image'=>$HValidationRules['driver_license_image'],
			'manufacturing_year'=>$HValidationRules['manufacturing_year'],
			'car_max_capacity'=>$HValidationRules['car_max_capacity'],
			'front_image'=>$HValidationRules['front_image'],
			'back_image'=>$HValidationRules['back_image'],
			'plate_letters'=>$HValidationRules['plate_letters'],
			'plate_numbers'=>$HValidationRules['plate_numbers'],
			'car_color'=>$HValidationRules['car_color'],
			'car_id_number'=>$HValidationRules['car_id_number'],
			'invitation_code'=>$HValidationRules['invitation_code'],
			'received_invitation_code'=>$HValidationRules['received_invitation_code'],
			'current_latitude' => ['sometimes','required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'current_longitude' => ['sometimes','required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }
	public function messages()
	{
	
		return [
			'first_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('First Name')]),
			'first_name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('First Name'),'max'=>255	]),
			'last_name.required'=>__('Please Enter :attribute' , ['attribute'=>__('Last Name')]),
			'last_name.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Last Name'),'max'=>255	]),
			'manufacturing_year.required'=>__('Please Enter :attribute' , ['attribute'=>__('Manufacturing Year')]),
			'manufacturing_year.integer'=>__('Please Enter Valid :attribute' , ['attribute'=>__('Manufacturing Year')]),
			
			
			'email.required'=>__('Please Enter :attribute' , ['attribute'=>__('Email')]),
			'email.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Email'),'max'=>255	]),
			'email.unique'=> __(':attribute Already Exist',['attribute'=>__('Email')]),
			
			
			'birth_date.required'=>__('Please Enter :attribute' , ['attribute'=>__('Birth Date')]),
			'birth_date.date_format'=>__('Invalid :attribute' , ['attribute'=>__('Birth Date')]),
			'birth_date.date'=>__('Invalid :attribute' , ['attribute'=>__('Birth Date')]),
			
			'phone.required'=>__('Please Enter :attribute' , ['attribute'=>__('Phone')]),
			'phone.unique'=> __(':attribute Already Exist',['attribute'=>__('Phone')]),
			
			'id_number.required'=>__('Please Enter :attribute' , ['attribute'=>__('Id Number')]),
			'id_number.unique'=> __(':attribute Already Exist',['attribute'=>__('Id Number')]),	
			
			// 'invitation_code.sometimes'=>__('Please Enter :attribute' , ['attribute'=>__('Invitation Code')]),
			'invitation_code.required'=>__('Please Enter :attribute' , ['attribute'=>__('Invitation Code')]),
			'received_invitation_code.exists'=>__('Invitation Code Not Found' ,[]),
			'invitation_code.unique'=> __(':attribute Already Exist',['attribute'=>__('Invitation Code')]),
			'invitation_code.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Invitation Code'),'max'=>255	]),
			
			'deduction_percentage.number'=>__('Please Enter Valid :attribute' , ['attribute'=>__('Deduction Percentage')]),
			'deduction_percentage.gte'=>__('Only Greater Than Minus One Allowed For :attribute' , ['attribute'=>__('Deduction Percentage')]),
			
			'country_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Country Name')]),
			'city_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('City Name')]),
			'make_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Make Name')]),
			'model_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Model Name')]),
			'size_id.required'=>__('Please Enter :attribute' , ['attribute'=>__('Car Size')]),
			
			'image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Image')]),
			'image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Image')]),
			'image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Image')]),

			'id_number_image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Id Number Image')]),
			'id_number_image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Id Number Image')]),
			'id_number_image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Id Number Image')]),
			
			'insurance_image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Insurance Image')]),
			'insurance_image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Insurance Image')]),
			'insurance_image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Insurance Image')]),
			
			'driver_license_image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Driver License Image')]),
			'driver_license_image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Driver License Image')]),
			'driver_license_image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Driver License Image')]),
			
			
			'front_image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Front Car Image')]),
			'front_image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Front Car Image')]),
			'front_image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Front Car Image')]),
			
			'back_image.required'=>__('Please Enter :attribute' , ['attribute'=>__('Back Car Image')]),
			'back_image.file'=>__('Please Enter Valid File For :attribute' , ['attribute'=>__('Back Car Image')]),
			'back_image.mimes'=>__('Unsupported File Type :attribute' , ['attribute'=>__('Back Car Image')]),
			
			
			'plate_letters.required'=>__('Please Enter :attribute' , ['attribute'=>__('Plate Letters')]),
			'plate_letters.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Plate Letters'),'max'=>255	]),
			
			'car_max_capacity.required'=>__('Please Enter :attribute' , ['attribute'=>__('Car Max Capacity')]),
			'car_max_capacity.gte'=>__('Only Greater Than Or Equal One Allowed For :attribute' , ['attribute'=>__('Car Max Capacity')]),
			
			'plate_numbers.required'=>__('Please Enter :attribute' , ['attribute'=>__('Plate Number')]),
			'plate_numbers.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Plate Number'),'max'=>255	]),
			
			'car_color.required'=>__('Please Enter :attribute' , ['attribute'=>__('Car Color')]),
			'car_color.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Car Color'),'max'=>255	]),
			
			'car_id_number.required'=>__('Please Enter :attribute' , ['attribute'=>__('Car Id Number')]),
			'car_id_number.max'=> __(':attribute Exceed The Max Letter Length :max Letter',['attribute'=>__('Car Id Number'),'max'=>255	]),
			
			
			'driver_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
			'driver_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
       
			'current_latitude.required' => __('Please Enter :attribute', ['attribute' => __('Latitude',[],getApiLang())],getApiLang()),
			'current_latitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Latitude')]),
			'current_longitude.required' => __('Please Enter :attribute', ['attribute' => __('Longitude',[],getApiLang())],getApiLang()),
			'current_longitude.regex'=>__('Invalid :attribute' , ['attribute'=>__('Longitude')]),
			
		];
	}
	
	
}
