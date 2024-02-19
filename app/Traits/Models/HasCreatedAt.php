<?php 
namespace App\Traits\Models;

use App\Helpers\HDate;

trait HasCreatedAt
{
	public function getCreatedAt()
	{
		return $this->created_at ;
	}
	public function getCreatedAtFormatted()
	{
		return HDate::formatForView($this->getCreatedAt(),true);
	}
	
}
