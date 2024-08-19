<?php 
namespace App\Traits\Models;
trait HasIsListingToOrdersNow 
{
	public function scopeOnlyListingToOrders($q){
        return $q->where('is_listing_to_orders_now',1);
    }
	public function getIsListingToOrders()
	{
		return (bool) $this->is_listing_to_orders_now ;
	}
	public function getIsListingToOrdersFormatted()
	{
		$isVerified = $this->getIsListingToOrders();
		return $isVerified ? __('Yes') : __('No');
	}
	public function toggleIsListingToOrders()
	{
		$this->is_listing_to_orders_now = ! $this->is_listing_to_orders_now ;
		$this->save();
	}
}
