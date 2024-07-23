<?php 
namespace App\Helpers;
use Illuminate\Support\Carbon;
// Date Helpers
class HDate
{
	const DEFAULT_DATE_FORMAT_AS_INPUT = 'Y-m-d';
	const DEFAULT_DATE_AS_OUTPUT = 'd/m/Y';
	const DEFAULT_DATE_TIME_FORMAT_AS_OUTPUT = 'd/m/Y g:i A';
	const DEFAULT_DATE_TIME_FORMAT_AS_INPUT= 'Y-m-d g:i A';
	const TIME_FORMAT = 'g:i A';
	public static function isValidDateFormat(?string $date , string $format)
	{
		return Carbon::createFromFormat($format, $date) !== false;
	}
	public static function formatForView(?string $date ,bool $onlyDate = false ){
		if(!$date){
			return __('N/A');
		}
		if($onlyDate) {
			return Carbon::make($date)->format(self::DEFAULT_DATE_AS_OUTPUT);
		}
			return Carbon::make($date)->format(self::DEFAULT_DATE_TIME_FORMAT_AS_OUTPUT);
	}
	public static function formatTimeForView(?string $time)
	{
		return $time ? Carbon::make($time)->format(self::TIME_FORMAT) : null ;
	}
	public static function formatTimeAsHoursAndMinutesForView($minutes){
		if($minutes <= 0){
			return 0;
		}
		$hours = floor($minutes / 60);
		$min = $minutes - ($hours * 60);
		if($hours >= 1 ){
			return $hours.":".$min .' '. __('Hours',[],getApiLang()); 
		}
		return $min .' '. __('Minutes');
	}
} 
