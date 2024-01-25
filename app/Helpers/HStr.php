<?php 
namespace App\Helpers;
class HStr{
	
	public static function camelizeWithSpace($input, $separator = '-')
	{
		return str_replace($separator, ' ', ucwords($input, $separator));
	}
	public static function getClassNameWithoutNameSpace($object){
		$class_parts = explode('\\', get_class($object));
 		 return end($class_parts);
	}
	
}
