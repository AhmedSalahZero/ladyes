<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model 
// implements HasMedia
{
	// city == govern
    use
	//  InteractsWithMedia,
	 IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory ;
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
	

	public function syncFromRequest($request){
		if ($request->has('name_en')){
			$this->name_en = $request->name_en;
		}
		if ($request->has('name_ar')){
			$this->name_ar = $request->name_ar;
		}
		if ($request->has('longitude')){
			$this->longitude = $request->longitude;
		}
		
		$this->save();
	}
	
}
