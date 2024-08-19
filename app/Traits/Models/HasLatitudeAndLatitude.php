<?php 
namespace App\Traits\Models;
trait HasLatitudeAndLatitude 
{
	public function getLatitude()
	{
		return $this->latitude ;
	}
	public function getLongitude()
	{
		return $this->longitude ; 
	}
}
