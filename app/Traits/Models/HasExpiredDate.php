<?php 
namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasExpiredDate 
{
	public function scopeOnlyAvailable(Builder $builder){
		$today = now()->format('Y-m-d');
		/**
		 ** marco [MacrosServiceProvider]
		 */
		return $builder->onlyAvailable($today );
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
