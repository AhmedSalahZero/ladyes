<?php 
namespace App\Traits\Models;

use App\Enum\DiscountType;

trait HasDiscountAmountWithDiscountType 
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
