<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * نوع التقارير وليكن يومي وشهري واسبوعي
 */
class ReportInterval  implements IEnum
{
	public const DAILY = 'daily'; 
	public const WEEKLY = 'weekly';
	public const MONTHLY  = 'monthly';
	
	public static function all():array 
	{
		return [
			self::DAILY=>__('Daily'),
			self::WEEKLY=>__('Weekly'),
			self::MONTHLY=>__('Monthly'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
