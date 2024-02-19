<?php 
namespace App\Enum;
class InformationSection {
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
		$formatted = [];
		$types = self::all();
		foreach($types as $value => $title){
			$formatted[] = ['title'=>$title,'value'=>$value];
		}
		return $formatted ; 
	}
}
