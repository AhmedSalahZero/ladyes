<?php 
namespace App\Traits\Models;

use App\Helpers\HHelpers;
use App\Helpers\HStr;
use App\Models\CarMake;
use App\Models\TravelCondition;

/**
 * * من هنا بنعرف كل يوزر مربوط بانهي شروط رحلة وليكن مثلا السواق الفولاني محدد اول اربع شروط
 * * او العميل الفلاني محدد اخر اتنين وهكذا
 */
trait HasTravelCondition
{
	public function travelConditions()
	{
		return $this->belongsToMany(TravelCondition::class,'user_travel_conditions','model_id','travel_condition_id')
		->where('model_type',HStr::getClassNameWithoutNameSpace($this))
		->withPivot(['model_type'])
		->withTimestamps();
	}
	/**
	 * * بنشوف هل الشخص دا بيحقق شروط الرحلة دي ولا لا
	 */
	public function satisfyConditions(array $travelConditionIds):bool 
	{
		if(!count($travelConditionIds)){ // no travel conditions required
			return true ;
		}
		$travelConditions = $this->travelConditions->pluck('id')->toArray() ;
		foreach($travelConditionIds as $travelConditionId){
			if(!in_array($travelConditionId,$travelConditions)){
				return false ;
			}
		}
		return true ;
	}
	
}
