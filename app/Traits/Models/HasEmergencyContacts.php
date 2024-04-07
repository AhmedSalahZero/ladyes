<?php 
namespace App\Traits\Models;

use App\Helpers\HStr;
use App\Models\EmergencyContact;


trait HasEmergencyContacts
{
	public function emergencyContacts()
	{
		return $this->belongsToMany(EmergencyContact::class,'model_emergency_contact','model_id','emergency_contact_id')
		->where('model_type',HStr::getClassNameWithoutNameSpace($this))
		->withPivot(['model_type','can_receive_travel_info'])
		->withTimestamps();
	}		
	
}
