<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;

/**
 * * مراحل الغاء الرحلة (خاصة بالعميل فقط) وعندنا ثلاث مراحل
 * * اما انه قام بالالغاء اثناء البحث عن سائق وليكن مثلا كان بيجرب التطبيق او بحث بالخطا
 * * او قبل وصول السائق او بعد وصولة 
 */
class CancellationReasonPhases  implements IEnum
{
	public const WHILE_SEARCHING = 'while_searching'; 
	public const BEFORE_DRIVER_ARRIVE = 'before_driver_arrive'; 
	public const AFTER_DRIVER_ARRIVE = 'after_driver_arrive'; 
	
	public static function all():array 
	{
		return [
			self::WHILE_SEARCHING=>__('While Searching',[],getApiLang()),
			self::BEFORE_DRIVER_ARRIVE=>__('Before Driver Arrive',[],getApiLang()),
			self::AFTER_DRIVER_ARRIVE=>__('After Driver Arrive',[],getApiLang()),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
