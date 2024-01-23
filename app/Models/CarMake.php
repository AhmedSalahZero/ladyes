<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CarMake extends Model implements HasMedia
{
    use InteractsWithMedia, IsBaseModel,HasDefaultOrderScope , HasTransNames , HasFactory ;
	
	public function drivers():HasMany
	{
		return $this->hasMany(Driver::class,'make_id','id');
	}
	public function models()
	{
		return $this->hasMany(CarModel::class,'make_id');
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
		if ($request->has('logo')){
			static::addMediaFromRequest('logo')->toMediaCollection('logo');
		}
		$this->save();
	}
	
}
