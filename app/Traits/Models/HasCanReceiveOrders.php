<?php 
namespace App\Traits\Models;
trait HasCanReceiveOrders 
{
	public function scopeOnlyCanReceiveOrders($q){
        return $q->where('can_receive_orders',1);
    }
	public function getCanReceiveOrders()
	{
		return (bool) $this->can_receive_orders ;
	}
	public function getCanReceiveOrdersFormatted()
	{
		$isVerified = $this->getCanReceiveOrders();
		return $isVerified ? __('Yes') : __('No');
	}
	public function toggleCanReceiveOrders()
	{
		$this->can_receive_orders = ! $this->can_receive_orders ;
		$this->save();
	}
}
