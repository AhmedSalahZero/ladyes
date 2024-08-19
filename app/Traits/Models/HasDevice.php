<?php 
namespace App\Traits\Models;


trait HasDevice 
{
	public function getDeviceId()
	{
		return $this->device_id ;
	}
	public function getDeviceType()
	{
		return $this->device_type ;
	}
	
}
