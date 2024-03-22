<?php 
namespace App\Traits\Models;


trait HasAmount 
{
	public function getAmount()
	{
		return $this->amount?:0 ;
	}
	public function getAmountFormatted()
	{
		$amount = $this->getAmount();
		return number_format($amount,0);
	}
	
	
	
}
