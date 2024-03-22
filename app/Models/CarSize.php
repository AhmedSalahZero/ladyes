<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasPrice;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSize extends Model
{
    use HasFactory,IsBaseModel,HasTransNames,  HasDefaultOrderScope,HasPrice,HasBasicStoreRequest ;
	
	const ECO_IMAGE = 'custom/images/eco.png';
	
	const PRIVATE_IMAGE = 'custom/images/private.png';
	
	const VIP_IMAGE = 'custom/images/vip.png';
	
	const FAMILY_IMAGE = 'custom/images/family.png';
	
	public function drivers()
	{
		return $this->hasMany(Driver::class,'size_id','id');
	}
	public function getPrivateImage():string 
	{
		return asset(self::PRIVATE_IMAGE);
	}
	public function getVipImage():string 
	{
		return asset(self::VIP_IMAGE);
	}
	public function getFamilyImage():string 
	{
		return asset(self::FAMILY_IMAGE);
	}
	public function getImage()
	{
		$image = $this->image;
		return $image && file_exists($image) ?  asset($image) : getDefaultImage() ;
	}
	/**
	 * * هنا السعر بناء علي الدولة
	 * 
	 */
	public function countryPrices()
	{
		return $this->belongsToMany(Country::class,'model_country_price','model_id','country_id')
		->where('model_type','CarSize')
		->withPivot(['price'])
		->withTimestamps();
	}
	public function getPrice(int $countryId){
	
		$countryPrice = $this->countryPrices->where('id',$countryId)->first() ;

		return $countryPrice ? $countryPrice->pivot->price : 0 ;
	}
	public function getPriceFormatted(int $countryId , string $lang)
	{
		$country = $this->countryPrices->where('country_id',$countryId)->first() ;
	}
	public function getCountriesIds()
	{
		return $this->countryPrices->pluck('id')->toArray();
	}
	
}
