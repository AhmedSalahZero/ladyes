<?php 
namespace App\Traits\Models;

use App\Enum\DiscountType;

trait HasAmountWithDiscountType 
{
	public function getDiscountType()
	{
		return $this->discount_type ;
	}
	public function getDiscountTypeFormatted()
	{
		$discountType = $this->getDiscountType() ;
		return $discountType ? __($discountType) : __('N/A');
	}
	public function getAmount()
	{
		return (float)$this->amount; 
	}
	public function getAmountFormatted()
	{
		$discountType = $this->getDiscountType();
		$amount = $this->getAmount() ;
		if($discountType == DiscountType::PERCENTAGE){
			return $amount .' %';
		}
		return $amount  ;
	}
}
