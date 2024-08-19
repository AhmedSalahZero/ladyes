<?php 
namespace App\Traits\Models;

use App\Models\City;

trait HasCity 
{
	public function city()
	{
		return $this->belongsTo(City::class , 'city_id','id');
	}	
	public function getCityId():int 
	{
		$city = $this->city ; 
		return $city ? $city->getId() : 0 ;
	}
	public function getCityName($lang)
	{
		$city = $this->city ; 
		return $city ? $city->getName($lang) : __('N/A') ;
	}
	
}
