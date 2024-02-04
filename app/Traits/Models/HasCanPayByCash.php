<?php 
namespace App\Traits\Models;
trait HasCanPayByCash 
{
	public function scopeOnlyCanPayByCash($q){
        return $q->where('can_pay_by_cash',1);
    }
	public function getCanPayByCash()
	{
		return (bool) $this->can_pay_by_cash ;
	}
	public function getCanPayByCashFormatted()
	{
		$canPayByCash = $this->getCanPayByCash();
		return $canPayByCash ? __('Yes') : __('No');
	}
	public function toggleCanPayByCash()
	{
		$this->can_pay_by_cash = ! $this->can_pay_by_cash ;
		$this->save();
	}
}
