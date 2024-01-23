<?php 
namespace App\Traits\Models;

use App\Models\Area;
use App\Models\City;

trait HasArea 
{
	public function area()
	{
		return $this->belongsTo(Area::class , 'area_id','id');
	}	
	public function getAreaId():int 
	{
		$area = $this->area ; 
		return $area ? $area->getId() : 0 ;
	}
	public function getAreaName($lang)
	{
		$area = $this->area ; 
		return $area ? $area->getName($lang) : __('N/A') ;
	}
}
