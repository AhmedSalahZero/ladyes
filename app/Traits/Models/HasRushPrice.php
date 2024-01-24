<?php 
namespace App\Traits\Models;


trait HasRushPrice 
{	
	public function getRushHourPrice()
	{
		return $this->rush_hour_price ; 
	}
	public function getRushHourPriceFormatted($lang)
	{
		return $this->rush_hour_price .' '.  $this->getCountrySymbol($lang) ;
	}
	
	
}
