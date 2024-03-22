<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;

class InformationSection implements IEnum {
	public const AFTER_DRIVER_SIGNUP = 'after_driver_login'; 
	public const CLIENT_PROFILE = 'client_profile'; 
	public static function all():array 
	{
		return [
			self::AFTER_DRIVER_SIGNUP => __('After Driver Signup'),
			self::CLIENT_PROFILE=>__('Client Profile')
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
