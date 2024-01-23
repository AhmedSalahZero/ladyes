<?php 
namespace App\Helpers;

use Illuminate\Support\Str;

class HHelpers 
{
	public static function getClassNameWithoutNameSpace($object){
		$class_parts = explode('\\', get_class($object));
 		 return end($class_parts);
	}
	public static function generateUniqueCodeForModel( string $modelName ,string $columnName,int $length){
			$modelFullName = 'App\Models\\'.$modelName;
			$randomCode = self::generateCodeOfLength($length); ;			
            $model = $modelFullName::where($columnName,$randomCode)->exists();
            if ($model) {
				return self::generateUniqueCodeForModel($modelName,$columnName,$length);
            }
			return $randomCode ; 
	}
	public static function generateCodeOfLength($length)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
		return $randomString ;
	}
}
