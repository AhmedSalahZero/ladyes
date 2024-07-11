<?php

namespace App\Traits\Models;

use App\Helpers\HHelpers;
use App\Models\DeviceToken;

trait HasDeviceTokens
{
	public function deviceTokens()
	{
		return $this->hasMany(DeviceToken::class,'model_id','id')
		->where('model_type',HHelpers::getClassNameWithoutNameSpace($this));
	}
	public function getDeviceTokens():array 
	{
		return $this->deviceTokens->pluck('device_token')->toArray();
	}
	public function routeNotificationForFcm()
    {
        return $this->getDeviceTokens();
    }

	public function syncDeviceTokens(string $deviceToken , ?string $deviceName = null ):void{
		$exists = in_array($deviceToken , $this->getDeviceTokens());
		if(!$exists){
			$this->deviceTokens()->create([
				'model_type'=>HHelpers::getClassNameWithoutNameSpace($this) , 
				'device_token'=>$deviceToken ,
				'device_name'=>$deviceName 
			]);
			
		}
		
	}
	
}
