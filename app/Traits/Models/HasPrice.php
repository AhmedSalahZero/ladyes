<?php 
namespace App\Traits\Models;


trait HasPrice 
{	
	public function getPrice()
	{
		return $this->price ; 
	}
	public function getPriceFormatted($lang)
	{
		return $this->price .' '.  $this->getCountrySymbol($lang) ;
	}
	
	
}
