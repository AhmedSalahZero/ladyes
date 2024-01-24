<?php 
namespace App\Traits\Models;


trait HasKmPrice 
{	
	public function getPrice()
	{
		/**
		 * * استخدم هذا السعر مع المدينه في حاله انك مش في وقت ذروة
		 * * اما في حاله وجود وقت ذروة استخدم السعر الخاص به
		 * * rush hor
		 */
		return $this->price ; 
	}
	public function getPriceFormatted($lang)
	{
			/**
		 * * استخدم هذا السعر مع المدينه في حاله انك مش في وقت ذروة
		 * * اما في حاله وجود وقت ذروة استخدم السعر الخاص به
		 * * rush hor
		 */
		
		return $this->price .' '.  $this->getCountrySymbol($lang) ;
	}
	
	public function getKmPrice()
	{
		return $this->km_price ; 
	}
	public function getKmPriceFormatted($lang)
	{
		return $this->km_price .' '.  $this->getCountrySymbol($lang) ;
	}
	
	
	public function getMinutePrice()
	{
		return $this->minute_price ; 
	}
	public function getMinutePriceFormatted($lang)
	{
		return $this->minute_price .' '.  $this->getCountrySymbol($lang) ;
	}
	
	public function getOperatingFeesPrice()
	{
		return $this->operating_fees ; 
	}
	public function getOperatingFeesFormatted($lang)
	{
		return $this->operating_fees .' '.  $this->getCountrySymbol($lang) ;
	}
	
}
