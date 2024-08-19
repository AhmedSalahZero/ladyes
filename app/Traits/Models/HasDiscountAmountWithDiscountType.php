<?php 
namespace App\Traits\Models;

use App\Enum\DiscountType;

trait HasDiscountAmountWithDiscountType 
{
	public function isPercentage():bool 
	{
		return $this->getDiscountType() == DiscountType::PERCENTAGE;
	}
	public function getDiscountType()
	{
		return $this->discount_type ;
	}
	public function getDiscountTypeFormatted()
	{
		$discountType = ucfirst($this->getDiscountType()) ;
		return $discountType ? __($discountType)  : __('N/A');
	}
	public function getDiscountAmount()
	{
		return (float)$this->discount_amount ; 
	}
	public function getDiscountAmountFormatted()
	{
		$discountType = $this->getDiscountType();
		$discountAmount = $this->getDiscountAmount() ;
		if($discountType == DiscountType::PERCENTAGE){
			return $discountAmount .' %';
		}
		return $discountAmount  ;
	}
}
