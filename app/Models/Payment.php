<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Traits\Models\HasOperationalFees;
use App\Traits\Models\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * تحتوي علي بيانات الدفع وليكن مثلا نوع عمليه الدفع كاش مثلا او من المحفظة والمقدار و والغرمات وقيمة الكوبون ان وجد الخ
 */
class Payment extends Model
{
    use HasFactory , HasPrice , HasOperationalFees;
	
	protected $guarded = [
		'id'
	];
	public function travel()
	{
		return $this->belongsTo(Travel::class , 'travel_id','id');
	}
	public function getType():string 
	{
		/**
		 * * visit PaymentType::class for all possible values 
		 */
		return $this->type ;
	}
	public function getTypeFormatted()
	{
		$paymentMethod = PaymentType::all();
		return $paymentMethod[$this->getType()] ;
	}
	public function getStatus()
	{
		return $this->status ; 
	}
	public function getStatusFormatted()
	{
		$paymentStatus = PaymentStatus::all();
		return $paymentStatus[$this->getStatus()] ;
	}
	
	
	public function client()
	{
		return $this->belongsTo(Client::class,'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace(new Client));
	}
	public function driver()
	{
		return $this->belongsTo(Driver::class,'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace(new Driver));
	}
	public function transaction()
	{
		return $this->hasOne(Transaction::class,'type_id','id')->where('transactions.type',TransactionType::PAYMENT);
	}
		
	public function getCouponDiscountAmount()
	{
		return $this->coupon_amount ?:0;
	}
	public function getCouponDiscountAmountFormatted()
	{
		$couponDiscountAmount = $this->getCouponDiscountAmount();
		return number_format($couponDiscountAmount,0) ;
	}
	
}
