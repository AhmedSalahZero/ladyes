<?php 
namespace App\Enum;
class DiscountType
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
		$formatted = [];
		$discountTypes = self::all();
		foreach($discountTypes as $value => $title){
			$formatted[] = ['title'=>$title,'value'=>$value];
		}
		return $formatted ; 
	}
}
