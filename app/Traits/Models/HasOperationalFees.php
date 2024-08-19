<?php 
namespace App\Traits\Models;

/**
 * * رسوم التشغيل 
 */
trait HasOperationalFees
{	
	public function getOperationalFees()
	{
		return $this->operational_fees ; 
	}
	public function getOperationalFeesFormatted($lang)
	{
		return $this->getOperationalFees .' '.  $this->getCurrencyFormatted($lang) ;
	}
	
	
}
