<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;

class DiscountType implements IEnum 
{
	public const FIXED = 'fixed'; 
	public const PERCENTAGE = 'percentage'; 
	public static function all():array 
	{
		return [
			self::FIXED => __('Fixed'),
			self::PERCENTAGE=>__('Percentage')
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
