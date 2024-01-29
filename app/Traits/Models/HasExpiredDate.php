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
	public function getIsAvailable()
	{
		$today = now()->format('Y-m-d');
		
		return (bool) $this->start_date <= $today && $this->end_date > $today ;
	}
	public function getIsAvailableFormatted()
	{
		$isAvailable = $this->getIsAvailable();
		return $isAvailable ? __('Yes') : __('No');
	}

}
