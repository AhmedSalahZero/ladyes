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
	
		 /**
	 * * الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة
	 */
	public function getCancellationFeesForClient()
	{
		return $this->cancellation_fees_for_client?:0 ;
	}
	public function getCancellationFeesForClientFormatted($lang)
	{
		return $this->getCancellationFeesForClient() . ' ' . $this->getCurrencyFormatted($lang) ;
	}
		/**
		** هي رسوم اضافية يدفع العميل لزوم الدفع بالكاش
	 */
	public function getCashFees()
	{
		return $this->cash_fees ?: 0 ;
	}
	public function getCashFeesFormatted($lang)
	{
		return $this->getCashFees() . ' ' . $this->getCurrencyFormatted($lang) ;
	}
	/**
	 * * الرسوم اللي هيتم تطبيقها علي السائق في حالة قام بالغاء الرحلة
	 */
	public function getBonusAfterFirstSuccessTravel()
	{
		return $this->first_travel_bonus ?: 0 ;
	}
	public function getBonusAfterFirstSuccessTravelFormatted($lang)
	{
		return $this->getBonusAfterFirstSuccessTravel() . ' ' . $this->getCurrencyFormatted($lang) ;
	}
	
}
