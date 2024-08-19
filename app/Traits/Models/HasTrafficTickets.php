<?php 
namespace App\Traits\Models;
trait HasTrafficTickets 
{
	public function scopeOnlyHasTrafficTickets($q){
        return $q->where('has_traffic_tickets',1);
    }
	public function getHasTrafficTickets()
	{
		return (bool) $this->has_traffic_tickets ;
	}
	public function getHasTrafficTicketsFormatted()
	{
		$hasTrafficTickets = $this->getHasTrafficTickets();
		return $hasTrafficTickets ? __('Yes') : __('No');
	}
	public function toggleHasTrafficTickets()
	{
		$this->has_traffic_tickets = ! $this->has_traffic_tickets ;
		$this->save();
	}
}
