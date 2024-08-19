<?php 
namespace App\Traits\Models;

use App\Helpers\HDate;

trait HasStartedAtAndEndedAt
{
	public function getStartedAt()
	{
		return $this->started_at;
	}
	public function getStartedAtFormatted($onlyDate = false)
	{
		return HDate::formatForView($this->getStartedAt(),$onlyDate);
	}
	public function getEndedAt()
	{
		return $this->ended_at;
	}
	public function getEndedAtFormatted($onlyDate = false)
	{
		return HDate::formatForView($this->getEndedAt(),$onlyDate);
	}
}
