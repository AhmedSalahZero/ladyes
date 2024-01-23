<?php

namespace App\Models;

use App\Models\CarMake;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasMake;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarModel extends Model implements HasMedia
{
    use InteractsWithMedia, IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory ,HasMake;
	
	public function drivers():HasMany
	{
		return $this->hasMany(Driver::class,'model_id','id');
	}
	
	public function registerMediaCollections(): void
	{
		 $this->addMediaCollection('logo')->singleFile();
	}
	
	public function syncFromRequest($request){
		if ($request->has('name_en')){
			$this->name_en = $request->name_en;
		}
		if ($request->has('name_ar')){
			$this->name_ar = $request->name_ar;
		}
		if ($request->has('make_id')){
			$this->make_id = $request->make_id;
		}
		if ($request->has('logo')){
			static::addMediaFromRequest('logo')->toMediaCollection('logo');
		}
		$this->save();
	}
	
}
