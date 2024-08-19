<?php 
namespace App\Traits\Models;

use App\Models\CarMake;

trait HasMake
{
		// ماركة السيارة
		public function make()
		{
			return $this->belongsTo(CarMake::class,'make_id','id');
		}
		public function getMakeId():int 
		{
			$carMake = $this->make ; 
			return $carMake ? $carMake->getId() : 0 ;
		}
		public function getMakeName($lang)
		{
			$carMake = $this->make ; 
			return $carMake ? $carMake->getName($lang) : __('N/A') ;
		}
}
