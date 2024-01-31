<?php 
namespace App\Enum;
class InformationSection {
	public const AFTER_LOGIN = 'after_login'; 
	public const PROFILE = 'profile'; 
	public static function all():array 
	{
		return [
			self::AFTER_LOGIN => __('After Login'),
			self::PROFILE=>__('Profile')
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
