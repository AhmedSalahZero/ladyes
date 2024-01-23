<?php 
namespace App\Helpers;
class HAuth 
{
	public static function getActiveGuard(){

		foreach(array_keys(config('auth.guards')) as $guard){
		
			if(auth()->guard($guard)->check()) return $guard;
		
		}
		return null;
		}
}
