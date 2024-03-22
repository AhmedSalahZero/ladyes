<?php 
namespace App\Interfaces;
interface IEnum {
	public static function all():array;
	public static function allFormattedForSelect2():array; 
}
