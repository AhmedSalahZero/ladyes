<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * هي انواع الاشعارات اللي بتتبعت للعميل او السائق وليكن مثل غرامة او اشعار افتراضي او نجاح ايداع
 * * واللي من خلالها هنحدد نوع الايكونة اللي هتكون جنب الاشعار في التطبيق 
 */
class AppNotificationType  implements IEnum
{
	public const DEFAULT = 'default'; 
	public const FINE = 'find'; // غرامة  مثلا
	public const DEPOSIT  = 'deposit';
	public const WITHDRAWAL  = 'withdrawal';
	public const INFO  = 'info';
	public const ALERT = 'alert'; // وليكن مثلا الكابتن ارسل اليك رسالة
	public const WARNING = 'warning'; // وليكن مثلا تم حظر حسابك
	public const LOCATION = 'location'; // وليكن مثلا السائق وصل الي مكانك 
	
	public static function all():array 
	{
		return [
			self::DEFAULT => __('Default'),
			self::FINE=>__('Fine'),
			self::DEPOSIT=>__('Deposit'),
			self::WITHDRAWAL=>__('Withdrawal'),
			self::INFO=>__('Info'),
			self::ALERT=>__('Alert'),
			self::WARNING=>__('Warning'),
			self::LOCATION=>__('Location'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
