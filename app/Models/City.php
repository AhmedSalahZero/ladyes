<?php

namespace App\Models;

use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasGeoLocation;
use App\Traits\Models\HasKmPrice;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model 
// implements HasMedia
{

	
/**
 * * city == govern 
 */

	 use IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory,HasKmPrice ,HasGeoLocation;
	
	 public static function getCityFromLatitudeAndLongitude(Country $country , string $latitude , string $longitude )
	 {
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		/**
		 * * اسم المحافظة
		 */
		$cityName = $googleDistanceMatrixService->getCityNameFromLatitudeAndLongitude($latitude,$longitude);
		foreach($country->cities as $city){
			$currentCityName = $googleDistanceMatrixService->getCityNameFromLatitudeAndLongitude($city->getLatitude(),$city->getLongitude());
			if($currentCityName === false){
				continue;
			}
			if($cityName == $currentCityName){
				return $city;
			}
		}
		return false ;
	 }
	 public function rushHours():HasMany
	 {
		return $this->hasMany(RushHour::class,'city_id','id');
	 }
	public function country()
	{
		return $this->belongsTo(Country::class , 'country_id','id');
	}	
	public function getCountry():?Country
	{
		return $this->country ;
	}
	public function getCountryId():int 
	{
		$country = $this->country ; 
		return $country ? $country->getId() : 0 ;
	}
	public function getCountryName($lang)
	{
		$country = $this->country ; 
		return $country ? $country->getName($lang) : __('N/A') ;
	}
	public function getCountryPhoneCode()
	{
		$country = $this->country ; 
		return $country ? $country->getPhoneCode() : null ;
	}
	public function getCountryIso2()
	{
		$country = $this->country ; 
		return $country ? $country->getIso2() : null ;
	}
	public function getCurrency()
	{
		$country = $this->country ; 
		return $country ? $country->getCurrency() : null ;
	}
	public function getCurrencyFormatted(string $lang = null)
	{
		$lang = is_null($lang) ? getApiLang() : $lang ; 
		$country = $this->country ; 
		return $country ? __($country->getCurrency(),[],$lang) : null ;
	}
	/**
	 * * بنشوف هل المدينة دي في الوقت دا هل هي في وقت ذروة ولا لا 
	 */
	public function isInRushHourAt(?string $dateTime){
		if(is_null($dateTime)){
			return false ;
		}
		$time = Carbon::make($dateTime)->format('H:i:');
		$isInRushHour = false ;
		foreach($this->rushHours as $rushHour){
			$startFrom = $rushHour->getStartFrom();
			$endFrom = $rushHour->getEndFrom();
			if($time >= $startFrom && $time <= $endFrom){
				return $rushHour ;
			}
		}
		return $isInRushHour;
	}
	public function syncFromRequest($request){
		if ($request->has('name_en')){
			$this->name_en = $request->name_en;
		}
		if ($request->has('name_ar')){
			$this->name_ar = $request->name_ar;
		}	
		// if ($request->has('price')){
		// 	$this->price = $request->price;
		// }
		if ($request->has('km_price')){
			$this->km_price = $request->km_price;
		}
		if ($request->has('minute_price')){
			$this->minute_price = $request->minute_price;
		}
		if ($request->has('operating_fees')){
			$this->operating_fees = $request->operating_fees;
		}
		if ($request->has('percentage')){
			$this->percentage = $request->percentage;
		}
		if ($request->has('country_id')){
			$this->country_id = $request->country_id;
		}
				
		$this->save();
		/**
		 * * insert relationships 
		 */
		if($request->has('rush_hours')){
			$this->rushHours()->delete();
			foreach($request->get('rush_hours',[]) as $rushHourArr)
			{
				$this->rushHours()->create($rushHourArr);
			}
		}
		return $this ;
		

	}
	
	

	
}
