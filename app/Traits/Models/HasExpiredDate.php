<?php 
namespace App\Traits\Models;
trait HasExpiredDate 
{
	public function scopeOnlyAvailable($q){
		$today = now()->format('Y-m-d');
		/**
		 * marco [MarcoServiceProvider]
		 */
		return $q->onlyAvailable($today );
    }
	// use isAvailableForUsing for coupons 
	public function getIsAvailable()
	{
		$today = now()->format('Y-m-d');
		if($this->start_date && $this->end_date){
			return (bool) $this->start_date <= $today && $this->end_date > $today ;
		}
		if($this->start_date){
			return  (bool) $this->start_date <= $today  ;
		}
		return true ;
		
	}
	public function getIsAvailableFormatted()
	{
		$isAvailable = $this->getIsAvailable();
		return $isAvailable ? __('Yes') : __('No');
	}

}
