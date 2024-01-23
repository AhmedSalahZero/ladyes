<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasLatitudeAndLatitude;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Area extends Model 
// implements HasMedia
{
	// city == govern
    use
	//  InteractsWithMedia,
	 IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory,HasLatitudeAndLatitude,HasCountry,HasCity ;
	
	

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
		if ($request->has('latitude')){
			$this->latitude = $request->latitude;
		}
		if ($request->has('city_id')){
			$this->city_id = $request->city_id;
		}
		$this->save();
	}
	
}
