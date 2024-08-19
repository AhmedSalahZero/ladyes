<?php 
namespace App\Traits\Models;

use App\Helpers\HDate;

trait HasStartAndEndTime 
{
	public function getStartFrom()
	{
		return $this->start_time ;
	}
		
	public function getStartFromFormatted()
	{
		return HDate::formatTimeForView($this->start_time);
	}
	public function getEndFrom()
	{
		return $this->end_time ;
	}
	public function getEndFromFormatted()
	{
		return HDate::formatTimeForView($this->end_time) ;
	}
	
}
