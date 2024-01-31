<?php 
namespace App\Helpers;

use App\Helpers\HAuth;
use App\Rules\AmountOrPercentageRule;
use App\Rules\ValidPhoneNumberRule;
use Illuminate\Validation\Rule;

class HValidation
{
	const MAX_DESCRIPTION_LENGTH = 800; 
	public static function rules( ?string $tableName = null  , $model = null ,  bool $isRequired = true , array $uniquePerModelArr= [] , array $additionalRules = [] ):array 
	{
		$vendorOrEmployee =  Request()->user(HAuth::getActiveGuard());
		$vendorOrEmployee = $vendorOrEmployee ?: optional();
		$currentItemId = $isRequired === 'required' || !$model ? 0 : $model->id;
		// $uniqueExcept = $currentItemId;
		$isRequired = $isRequired && !$model ? 'required' : 'sometimes'; 
		return array_merge([
			'app_name_en'=>['required' , 'max:255'],
			'app_name_ar'=>['required' , 'max:255'],
			'app_phone'=>['required' , 'max:255'],
			'app_email'=>['required' , 'max:255'],
			'app_logo'=>'sometimes|file|mimes:'.self::getAllowedUploadImageAsString(),
			'fav_icon'=>'sometimes|file|mimes:'.self::getAllowedUploadImageAsString(),
			'facebook_url'=>['sometimes','url','max:255'] ,
			'instagram_url'=>['sometimes','url','max:255'] ,
			'twitter_url'=>['sometimes','url','max:255'] ,
			'youtube_url'=>['sometimes','url','max:255'] ,
			'WHATSAPP_APP_KEY'=>['WHATSAPP_APP_KEY','max:255'] ,
			'WHATSAPP_AUTH_KEY'=>['WHATSAPP_AUTH_KEY','max:255'] ,
			
			'ids' => 'required|array',
			'ids.*'=>'required|exists:'.$tableName.',id',
			'first_name'=>[$isRequired,'max:255'] ,
			'last_name'=>[$isRequired,'max:255'] ,
			'plate_numbers'=>[$isRequired,'max:255'] ,
			'plate_letters'=>[$isRequired,'max:255'] ,
			'car_color'=>[$isRequired,'max:255'] ,
			'car_id_number'=>[$isRequired,'max:255'] ,
			'name'=>[$isRequired,'max:255','unique:'.$tableName .',name'.','.$currentItemId] ,
			'name_en'=>[$isRequired,'max:255','unique:'.$tableName .',name_en'.','.$currentItemId] ,
			'name_ar'=>[$isRequired,'max:255','unique:'.$tableName .',name_ar'.','.$currentItemId] ,
			
			'description_en'=>[$isRequired,'max:500'] ,
			'description_ar'=>[$isRequired,'max:500'] ,
			
			'email'=>[$isRequired,'max:255','unique:'.$tableName .',email'.','.$currentItemId] ,
			'invitation_code'=>['sometimes','required','max:255','unique:'.$tableName .',invitation_code'.','.$currentItemId] ,
			'password'=>[$isRequired] ,
			'deduction_percentage'=>['sometimes','nullable','numeric','gte:-1'] ,
			'driving_range'=>['sometimes','nullable'] ,
			'role_name'=>[$isRequired] ,
			// start images validation
			
			'image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'id_number_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'insurance_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			// 'insurance_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'front_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'back_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'driver_license_image'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			'logo'=>$isRequired.'|file|mimes:'.self::getAllowedUploadImageAsString(),
			

			'amount_or_percentage'=> ['required','gt:0',new AmountOrPercentageRule],
			
			// 'image'=>'sometimes',
			
			
			// $isRequired 
			
			'sort'=>'sometimes|numeric|gt:0',
			'status'=>'sometimes|numeric|in:0,1',
			'price'=>[$isRequired,'numeric','gte:0'] ,
			'max_discount'=>['sometimes','numeric','gte:0'] ,
			'discount'=>['sometimes','numeric','gte:0'] ,
			'cost_price'=>['sometimes','numeric','gte:0'] ,
			'pricing_method'=>[$isRequired,'numeric','in:1,2,3'] ,
			'cost_calculation_method'=>[$isRequired,'numeric','in:1,2'] ,
			'device_status_id'=>[$isRequired,'numeric','in:1,2'] ,
			'price_method_price'=>['required_if:cost_calculation_method,==1,','numeric','gte:0'] ,
			'product_min_price'=>['sometimes','numeric','gte:0'] ,
			'order_min_price'=>['sometimes','numeric','gte:0'] ,
			
			'tax_group_id'=>['sometimes' , Rule::exists('tax_groups','id')->where('vendor_id',$vendorOrEmployee->id)] ,
			'store_house_id'=>['sometimes' , Rule::exists('store_houses','id')->where('vendor_id',$vendorOrEmployee->id)] ,
			// 'apply_order_type'=>['sometimes' , Rule::exists('vendor_order_types','id')->where('vendor_id',$vendorOrEmployee->id)] ,
			'description_en'=>'sometimes|max:'.self::MAX_DESCRIPTION_LENGTH,
			'description_ar'=>'sometimes|max:'.self::MAX_DESCRIPTION_LENGTH,
			'is_active'=>'sometimes|in:0,1',
			'can_receive_travel_info'=>'sometimes|in:0,1',
			'taxable'=>'sometimes|in:0,1',
			'ded_tax_amount'=>'sometimes|in:0,1',
			// 'tax_discount_amount'=>'required_ifsometimes|in:0,1',
			'mobile'=>'sometimes|max:255',
			'category_id'=>[$isRequired , Rule::exists('product_categories','id')->where('vendor_id',$vendorOrEmployee->id)],
			'make_id'=>[$isRequired , Rule::exists('car_makes','id')],
			'model_id'=>[$isRequired , Rule::exists('car_models','id')],
			'size_id'=>[$isRequired , Rule::exists('car_sizes','id')],
			'country_id'=>[$isRequired , Rule::exists('countries','id')],
			'city_id'=>[$isRequired , Rule::exists('cities','id')],
			'phone'=>[$isRequired,'unique:'.$tableName .',phone'.','.$currentItemId,new ValidPhoneNumberRule(Request()->get('country_id'))],
			'id_number'=>[$isRequired,'unique:'.$tableName .',id_number'.','.$currentItemId],
			'manufacturing_year'=>[$isRequired ,'digits:4','integer','min:1900'],
			'car_max_capacity'=>[$isRequired ,'gte:1'],
			'stock_category_id'=>[$isRequired , Rule::exists('stock_categories','id')->where('vendor_id',$vendorOrEmployee->id)],
			'start_date'=>$isRequired.'|required|date|date_format:Y-m-d',
			'end_date'=>$isRequired.'|required|date|date_format:Y-m-d|after:start_date',
			'storage_unit'=>$isRequired.'|required',
			'recipe_unit'=>$isRequired.'|required',
			'recipe_unit_quantity'=>$isRequired.'|required',
			'start_date_time'=>$isRequired.'|required|date_format:Y-m-d H:i|after_or_equal:now',
			'end_date_time'=>$isRequired.'|required|date_format:Y-m-d H:i|after:start_date_time',
			'start_work_time'=>'sometimes|date_format:H:i',
			'end_work_time'=>'sometimes|date_format: H:i',
			'end_stock_date'=>'sometimes|date|date_format:H:i',
			'created_at'=>$isRequired.'|required|date|date_format:Y-m-d',
			'event_type'=>$isRequired.'|in:1,2,3,4',
			'discount_type'=>'required|in:fixed,percentage',
			'decrease_price_amount'=>'required_if:event_type,=,1|numeric|gte:0',
			'increase_price_amount'=>'required_if:event_type,=,2|numeric|gte:0',
			'decrease_price_percent'=>'required_if:event_type,=,3|numeric|between:0,100',
			'increase_price_percent'=>'required_if:event_type,=,4|numeric|between:0,100',
			'maximum_discount_amount'=>'sometimes|required|numeric|gt:0',
			'tax_number'=>['sometimes' , 'max:255'],
			'up_invoices'=>['sometimes' , 'max:255'],
			'down_invoices'=>['sometimes' , 'max:255'],
			'address'=>['sometimes' , 'max:255'],
			'latitude' => [$isRequired , 'between:-90,90'],
            'longitude' => [$isRequired,'between:-180,180'],			
			// 'branch_ids' => 'sometimes|array',
			// 'branch_ids.*'=>['required',Rule::exists('branches', 'id')->where('vendor_id',$vendorOrEmployee->id)],
			
			// 'apply_order_type' => 'sometimes|array',
			// 'apply_order_type.*'=>['required',Rule::exists('vendor_order_types', 'id')->where('vendor_id',$vendorOrEmployee->id)],
			
			// 'card_number'=>[$isRequired  == 'required' ? 'required_without:card_id' : 'sometimes'],
			// 'device_type_id'=>[$isRequired , 'exists:device_types,id'],
			// 'tax_discount_amount'=>'required_if:ded_tax_amount,=,1|numeric|gte:0',
		] , $additionalRules);
	}
	public static function getAllowedUploadImage():array 
	{
		return [
			'png','jpg','jpeg','bmp'
		];
	}
	public static function getAllowedUploadImageAsString():string
	{
		return implode(',',self::getAllowedUploadImage());
	}
}
