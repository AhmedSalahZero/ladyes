<?php 
namespace App\Helpers;

use Illuminate\Validation\Rule;


class HIDsValidation
{
	public static function rules($vendorOrEmployee, string $firstTableName,$firstTableIdName, string $secondTableName , string $secondTableIdsName):array 
	{
		
		return array_merge([
			$firstTableIdName => 'required|array',
			$firstTableIdName.'.*'=>['required',Rule::exists($firstTableName, 'id')                     
			->where('vendor_id', $vendorOrEmployee->id )],
			$secondTableIdsName => 'sometimes|array',
			$secondTableIdsName.'.*'=>['sometimes',Rule::exists($secondTableName, 'id')                     
			->where('vendor_id', $vendorOrEmployee->id )],
		] );
	}
}
