<?php 
namespace App\Traits\Models;
trait HasStopPointLatitudesAndLongitudes 
{
	public function getStopPointLatitudes():array
	{
		return (array)$this->stop_point_latitudes ;
	}
	public function getStopPointLongitudes():array 
	{
		return (array)$this->stop_point_longitudes  ; 
	}
}
