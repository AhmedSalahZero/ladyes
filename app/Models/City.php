<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasPrice;
use App\Traits\Models\HasRushPrice;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model 
// implements HasMedia
{
	public const RUSH_HOUR_START_TIME = '06'; // 6:00 AM
	public const RUSH_HOUR_END_TIME = '18'; // 6:00 PM
	// city == govern
    use
	//  InteractsWithMedia,
	 IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory,HasPrice,HasRushPrice ;
	 public static function getRushHourPercentage(?Carbon $dateTime = null) {
		// نسبه للعرض فقط
		$dateTime = $dateTime ?: now();
		$dateTime = Carbon::create(2024,1,23,13,35);
		
		$morning = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 11, 0, 0); //set time to 08:00
		$evening = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 13, 30, 0); //set time to 18:00
		
		$currentDataTime = Carbon::make($dateTime) ;
		
		 $hour = $dateTime->format('H');

		//  من الساعة 6 صباحا وحتى الساعة 10 صباحا
		 if($hour >= 6 && $hour <= 10){
			return '5/5';			
		}
		// من الساعة 11 صباحا وحتى الساعة 1:30 الظهر
		 if($dateTime->between($morning,$evening)){
			 return '4.5/5';
			}
		 // من الساعة 4 العصر وحتى الساعة 6 المغرب
		 if($hour >= 16 && $hour <= 18){
			return '5/5';
		 }
		  // من الساعة 7 العصر وحتى الساعة 9 المغرب
		  if($hour >= 19 && $hour <= 21){
			return '4/5';
		 }
		 if(
			$currentDataTime->between(Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 22, 0, 0) , Carbon::create($dateTime->year, $dateTime->month, $dateTime->addDay()->day, 01, 0, 0))
		 ){
			return '3/5';
		 }
		
		 return null ;
	 }
	public function country()
	{
		return $this->belongsTo(Country::class , 'country_id','id');
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
	public function getCountrySymbol($lang)
	{
		$country = $this->country ; 
		return $country ? $country->getCurrencySymbol($lang) : null ;
	}

	public function syncFromRequest($request){
		if ($request->has('name_en')){
			$this->name_en = $request->name_en;
		}
		if ($request->has('name_ar')){
			$this->name_ar = $request->name_ar;
		}	
		if ($request->has('price')){
			$this->price = $request->price;
		}
		if ($request->has('rush_hour_price')){
			$this->rush_hour_price = $request->rush_hour_price;
		}
		if ($request->has('country_id')){
			$this->country_id = $request->country_id;
		}
		// if ($request->has('longitude')){
		// 	$this->longitude = $request->longitude;
		// }
		
		$this->save();
	}
	
}
