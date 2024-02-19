<?php 
namespace App\Traits\Models;
trait HasCountry 
{
	public function getCountryId():int 
	{
		$city = $this->city ;
		if($city){
			return $city->getCountryId() ;
		}
		$country = $this->country ;
		return $country ? $country->getId() : 0 ; 
	}
	public function getCountryName($lang):string 
	{
		$city = $this->city ;
		if($city){
			return $city->getCountryName($lang);
		}
		$country = $this->country ;
		return $country ? $country->getName($lang) : __('N/A') ; 
	}
	public function getCountryPhoneCode():?string 
	{
		$city = $this->city ;
		if($city){
			return $city->getCountryPhoneCode() ;
		}
		$country = $this->country ; 
	
		return $country ? $country->getPhoneCode():null;
	}
	public function getCountyIso2():?string 
	{
		$city = $this->city ;
		if($city){
			return $city->getCountryIso2() ;
		} 
		$country = $this->country ;
		
		return $country ? $country->getIso2() : null ;
	}
	
	
	
}
