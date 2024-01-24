<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
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

    use
	//  InteractsWithMedia,
	 IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory,HasKmPrice ;
	 
	//  public static function getRushHourPercentage(?Carbon $dateTime = null) {
	// 	// نسبه للعرض فقط
	// 	$dateTime = $dateTime ?: now();
	// 	$dateTime = Carbon::create(2024,1,23,13,35);
		
	// 	$morning = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 11, 0, 0); //set time to 08:00
	// 	$evening = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 13, 30, 0); //set time to 18:00
		
	// 	$currentDataTime = Carbon::make($dateTime) ;
		
	// 	 $hour = $dateTime->format('H');

	// 	//  من الساعة 6 صباحا وحتى الساعة 10 صباحا
	// 	 if($hour >= 6 && $hour <= 10){
	// 		return '5/5';			
	// 	}
	// 	// من الساعة 11 صباحا وحتى الساعة 1:30 الظهر
	// 	 if($dateTime->between($morning,$evening)){
	// 		 return '4.5/5';
	// 		}
	// 	 // من الساعة 4 العصر وحتى الساعة 6 المغرب
	// 	 if($hour >= 16 && $hour <= 18){
	// 		return '5/5';
	// 	 }
	// 	  // من الساعة 7 العصر وحتى الساعة 9 المغرب
	// 	  if($hour >= 19 && $hour <= 21){
	// 		return '4/5';
	// 	 }
	// 	 if(
	// 		$currentDataTime->between(Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, 22, 0, 0) , Carbon::create($dateTime->year, $dateTime->month, $dateTime->addDay()->day, 01, 0, 0))
	// 	 ){
	// 		return '3/5';
	// 	 }
		
	// 	 return null ;
	//  }
	 public function rushHours():HasMany
	 {
		return $this->hasMany(RushHour::class,'city_id','id');
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
