<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasLatitudeAndLatitude;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Country extends Model
    // implements HasMedia
{
    use
        //  InteractsWithMedia,
        IsBaseModel;
    use HasDefaultOrderScope ;
    use HasTransNames ;
    use HasFactory;
    use HasLatitudeAndLatitude ;

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }

    public function getPhoneCode()
    {
        return $this->phonecode ;
    }

    public function getIso3()
    {
        return $this->iso3;
    }

    public function getIso2()
    {
        return $this->iso2;
    }

    public function getNumericCode()
    {
        return $this->numericCode();
    }

    public function getCapital()
    {
        return $this->capital ;
    }

    public function getCurrency()
    {
        // for example [EGP]
        return $this->currency ;
    }

    public function getCurrencyName()
    {
        // for example [Egyptian pound]
        return $this->currency_name;
    }

    public function getCurrencySymbol()
    {
        // for example [ج.م]
        return $this->currency_symbol ;
    }

    public function getNationality()
    {
        // for example [Egyptian]
        return $this->nationality;
    }
    // public function registerMediaCollections(): void
    // {
    // 	 $this->addMediaCollection('logo')->singleFile();
    // }
    // public function syncFromRequest($request){
    // 	if ($request->has('name_en')){
    // 		$this->name_en = $request->name_en;
    // 	}
    // 	if ($request->has('name_ar')){
    // 		$this->name_ar = $request->name_ar;
    // 	}
    // 	if ($request->has('code')){
    // 		$this->name_ar = $request->name_ar;
    // 	}
    // 	if ($request->has('logo')){
    // 		static::addMediaFromRequest('logo')->toMediaCollection('logo');
    // 	}
    // 	$this->save();
    // }
}
