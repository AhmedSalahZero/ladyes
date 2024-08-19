<?php 
namespace App\Traits\Models;

use App\Helpers\HDate;

trait HasStartAndEndDate
{
	public function getStartDate()
	{
		return $this->start_date ;
	}
	public function getStartDateFormatted()
	{
		return HDate::formatForView($this->getStartDate(),true);
	}
	public function getEndDate()
	{
		return $this->end_date;
	}
	public function getEndDateFormatted()
	{
		return HDate::formatForView($this->getEndDate(),true);
	}
	
	
}
