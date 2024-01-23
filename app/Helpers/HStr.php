<?php 
namespace App\Helpers;
class HStr{
	
	public static function camelizeWithSpace($input, $separator = '-')
	{
		return str_replace($separator, ' ', ucwords($input, $separator));
	}
}
