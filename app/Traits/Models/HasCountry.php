<?php 
namespace App\Traits\Models;
trait HasCountry 
{
	public function getCountryId():int 
	{
		$city = $this->city ;
		return $city ? $city->getCountryId() :0; 
	}
	public function getCountryName($lang):string 
	{
		$city = $this->city ;
		return $city ? $city->getCountryName($lang) :__('N/A'); 
	}
	public function getCountryPhoneCode():?string 
	{
		$city = $this->city ;
		return $city ? $city->getCountryPhoneCode() :null; 
	}
	public function getCountyIso2():?string 
	{
		$city = $this->city ;
		return $city ? $city->getCountryIso2() :null; 
	}
	
}
