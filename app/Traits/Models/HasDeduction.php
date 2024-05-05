<?php 
namespace App\Traits\Models;

use App\Enum\DeductionType;
use libphonenumber\NumberFormat;

/**
 * * نسبة الاستقطاع او قيمة التي تخصم من السائق
 */
trait HasDeduction 
{
	public function getDeductionType()
	{
		return $this->deduction_type ;
	}
	public function getDeductionTypeFormatted()
	{
		return __(ucfirst($this->getDeductionType()) , [] , getApiLang());
	}
	public function getDeductionAmount()
	{
		return $this->deduction_amount ;
	}
	/**
	 * * دي اللي هنستخدمها في الحسابات
	 */
	public function getActualDeductionAmount()
	{
		$deductionType = $this->getDeductionType();
		$deductionAmount = $this->getDeductionAmount();
		if($deductionType == DeductionType::PERCENTAGE){
			return $deductionAmount / 100 ;
		}
		return $deductionAmount;
	}
	public function getDeductionAmountFormatted()
	{
		$deductionAmount = $this->getDeductionAmount();
		$deductionType = $this->getDeductionType();
		if($deductionType == DeductionType::PERCENTAGE){
			return number_format($deductionAmount,2) . ' %';
		}
		return number_format($deductionAmount,0);
	}
	/**
	 * * هنا هنحسب السائق مفروض ياخد قدية من الغرامة اللي اتطبقت علي العميل لما لغى الرحلة
	 * * ودا بعد اما نشيل منه نسبة او قيمة الاستقطاع
	 */
	public function getFeesAmountAfterDeduction($totalFeesAmount)
	{
		$deductionAmount = $this->getDeductionAmount();
		$deductionType = $this->getDeductionType();
		if($deductionType == DeductionType::PERCENTAGE){
			return $totalFeesAmount / 100 ;
		}
		return $totalFeesAmount - $deductionAmount  ;
	}
	
	
	
	
}
