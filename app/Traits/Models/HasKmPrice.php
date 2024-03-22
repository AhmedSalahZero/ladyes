<?php 
namespace App\Traits\Models;


trait HasKmPrice 
{	
	
	
	public function getKmPrice()
	{
		return $this->km_price ; 
	}
	public function getKmPriceFormatted($lang)
	{
		return $this->km_price .' '.  $this->getCurrencyFormatted($lang) ;
	}
	
	public function getCurrencyFormatted($lang)
	{
		$country = $this->country ; 
		return $country ? __($country->getCurrency(),[],getApiLang($lang)) : null ;
	}
	
	public function getMinutePrice()
	{
		return $this->minute_price ; 
	}
	public function getMinutePriceFormatted($lang)
	{
		return $this->minute_price .' '.  $this->getCurrencyFormatted($lang) ;
	}
	
	public function getOperatingFeesPrice()
	{
		return $this->operating_fees ; 
	}
	public function getOperatingFeesFormatted($lang)
	{
		return $this->operating_fees .' '.  $this->getCurrencyFormatted($lang) ;
	}
	
}
