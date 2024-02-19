<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasTransNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSize extends Model
{
    use HasFactory,IsBaseModel,HasTransNames;
	
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
	
}
