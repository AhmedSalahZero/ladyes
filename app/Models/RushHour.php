<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasKmPrice;
use App\Traits\Models\HasStartAndEndTime;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RushHour extends Model
{
    use  IsBaseModel,HasDefaultOrderScope  , HasFactory,HasKmPrice,HasCity,HasStartAndEndTime;
	protected $guarded = [
		'id'
	];
	

	
	public function syncFromRequest($request){
		if ($request->has('start_time')){
			$this->start_time = $request->start_time;
		}
		if ($request->has('end_time')){
			$this->end_time = $request->end_time;
		}
	
		if ($request->has('logo')){
			static::addMediaFromRequest('logo')->toMediaCollection('logo');
		}
		$this->save();
	}
	/**
	 * * For Example 2/5 
	 */
	public function getPercentage()
	{
		return $this->percentage ;
	}
	
	
	
}
