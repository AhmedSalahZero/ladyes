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
	public static function generateRandomString($length = 10, bool $onlyString = false , bool $onlyNumeric = false) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($onlyNumeric){
			$characters = '0123456789' ;
		}
		elseif($onlyString){
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public static function generateUniqueCodeForModel(string $modelName , string $columnName = 'code'){
		$code = self::generateRandomString(2,true ,false).self::generateRandomString(4,false ,true);
		$exists = ('\App\Models\\'.$modelName)::where($columnName,$code)->exists(); 
		if ($exists){
			return self::generateUniqueCodeForModel($modelName,$columnName);
		} 
		return $code  ;
	}	
}
