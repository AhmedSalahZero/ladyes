<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * حالات الرحلة هل لم تبدا بعد ام في الطريق ام تم الغائها ام السواق وصل للمكان ومنتظرك ام انتهت(وصلت) الخ
 */
class TravelStatus  implements IEnum
{
	public const NOT_STARTED_YET = 'not_started_yet'; 
	public const IS_WAITING_YOU = 'is_waiting_you';
	public const ON_THE_WAY = 'on_the_way';
	public const CANCELLED = 'cancelled';
	public const COMPLETED  = 'completed';
	
	public static function all():array 
	{
		return [
			self::NOT_STARTED_YET => __('Not Started Yet'),
			self::IS_WAITING_YOU=>__('Is Waiting You'), 
			self::ON_THE_WAY=>__('On The Way'),
			self::COMPLETED=>__('Completed'),
			self::CANCELLED=>__('Cancelled'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
