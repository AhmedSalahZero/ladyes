<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\DiscountType;
use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Traits\Models\HasOperationalFees;
use App\Traits\Models\HasPrice;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * تحتوي علي بيانات الدفع وليكن مثلا نوع عمليه الدفع كاش مثلا او من المحفظة والمقدار و والغرمات وقيمة الكوبون ان وجد الخ
 */
class Payment extends Model
{
    use HasFactory , HasPrice , HasOperationalFees , HasDefaultOrderScope;
		
	protected $guarded = [
		'id'
	];
	public function getId()
	{
		return $this->id ;
	}
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
	public function getPromotionActualAmount()
	{
		return $this->promotion_actual_amount ?:0;
	}
	public function getPromotionType()
	{
		return $this->promotion_type ;
	}
	public function getPromotionPercentage()
	{
		return $this->promotion_percentage ;
	}
	public function getPromotionAmountFormatted()
	{
		$promotionAmountOrPercentage = $this->getPromotionAmount();
		if($this->getPromotionType() == DiscountType::PERCENTAGE){
			return number_format($promotionAmountOrPercentage,2) . ' %' ;
		}
		return number_format($promotionAmountOrPercentage,0)  ;
	}
	public function storeForTravel(Travel $travel):self
	{
		if($travel->isPaid() 
		// && ! env('APP_ENV') == 'local'
		){
			return $this ;
		}
		$country = $travel->city->getCountry() ;
        $currencyName = $country->getCurrency();
		$currencyNameEn = $country->getCurrencyFormatted('en');
		$currencyNameAr = $country->getCurrencyFormatted('ar');
        $client = $travel->client;
		$travelId = $travel->id ;
        $travel->generateGiftCoupon();
        $couponAmount = $travel->getCouponDiscountAmount();
        $promotionType = $travel->getPromotionType();
        $paymentType = $travel->getPaymentMethod();
		$promotionPercentage =$travel->getPromotionPercentage(); 
		$cashFees = $travel->calculateCashFees();
		$totalFines = $travel->client->getTotalAmountOfUnpaid();
		$mainPriceWithoutDiscountAndTaxesAndCashFees =  $travel->calculateClientActualPriceWithoutDiscount();
		$promotionActualAmount = $travel->calculatePromotionAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$totalFines) ;
	
		
		$payment = $travel->payment()->create([
            'status' => PaymentStatus::SUCCESS,
            'currency_name' => $currencyName,
            'type' => $paymentType,
            'price' => $mainPriceWithoutDiscountAndTaxesAndCashFees ,
			'total_fines'=>$totalFines ,
            'promotion_type' => $promotionType,
			'promotion_percentage'=>$promotionPercentage , 
            'promotion_actual_amount' => $promotionActualAmount,
            'coupon_amount' => $couponAmount,
			'application_share'=>$travel->calculateApplicationShare(),
			'driver_share'=>$travel->calculateDriverShare(),
			'car_size_price'=>$travel->driver->carSize->getPrice($travel->city->getCountryId(), getApiLang()),
			'cash_fees'=>$cashFees ,
			'tax_amount'=>$taxAmount = $travel->calculateTaxAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$promotionActualAmount,$totalFines),
            'total_price' => $totalPrice = $travel->calculateClientTotalActualPrice($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$promotionActualAmount,$taxAmount,$cashFees,$totalFines),
            'model_id' => $travel->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($travel)
        ]);
		$paymentNoteEn = __('Your Payment Was Successfully For Travel :travelId',['travelId'=> $travel->getId() ],'en');
		$paymentNoteAr = __('Your Payment Was Successfully For Travel :travelId',['travelId'=> $travel->getId() ],'ar');
		
		$travel->client->sendAppNotification(__('Payment', [], 'en'), __('Payment', [], 'ar'), $paymentNoteEn, $paymentNoteAr, AppNotificationType::PAYMENT,$payment->id);
		
        /**
         * * اولا هنضيف ايداع بسعر الرحلة لمحفظتة
         */
		
		 $deposit = Deposit::create([
			'model_type'=> HHelpers::getClassNameWithoutNameSpace($client),
			'payment_method'=>$travel->getPaymentMethod(),
			'model_id'=>$client->id ,
			'amount'=>$totalPrice,
            'note_en' => $depositMessageEn = __('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($totalPrice), 'currency' => $currencyNameEn,'travelId'=>$travelId], 'en'),
            'note_ar' => $depositMessageAr =__('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($totalPrice), 'currency' => $currencyNameAr,'travelId'=>$travelId], 'ar'),
		 ]);
		 
		 $deposit->transaction()->create([
            'type' => TransactionType::DEPOSIT,
            'type_id' => $deposit->id,
            'model_id' => $client->id,
            'amount' => $totalPrice,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($client),
            'note_en' => $depositMessageEn,
            'note_ar' => $depositMessageAr,
        ]);

        /**
         * *بعدين هنشيل المبلغ دا من محفظته كرسوم للرحلة
         */
        $payment->transaction()->create([
            'type' => TransactionType::PAYMENT,
            'type_id' => $payment->id,
            'model_id' => $client->id,
            'amount' => $totalPrice * -1,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($client),
            'note_en' => __('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($totalPrice), 'currency' => __($currencyName, [], 'en'), 'travelId' => $travelId], 'en'),
            'note_ar' => __('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($totalPrice), 'currency' => __($currencyName, [], 'ar'), 'travelId' => $travelId], 'ar')
        ]);
		
		/**
		 * * لو العميل لديه غرمات موقع سوف نقوم بتسديدها
		 * * ودا لاننا لما بندفع فلوس الرحلة بيكون موجود معاها كل فلوس الغرمات السابقة وبالتالي
		 * * احنا 
		 */
		$travel->client->getUnpaidFines()->each(function(Fine $fine){
			$fine->addNewPaidTransaction($fine->getAmount());
		});
		
		/**
		 * * في حالة كانت هذه هي اول رحلة للعميل هنضفله بونص في حسابه في حالة لو كانت قيمة البونص في الادمن اكبر من صفر
		 */

		(new Bonus())->storeForFirstTravel($travel);
		
		/**
		 * * هنا سوف نعطي السائق حصتة  
		 */
		$deposit = (new Deposit())->storeNewForDriverAsTravelCompleted($travel);
		
		$travel->sendTravelCompletedMessageForClient();
		
		return $payment ;
	}
	public function getPrice():float 
	{
		return $this->price ?: 0 ;
	}
	public function getPriceFormatted():string
	{
		return number_format($this->getPrice());
	}	
	public function getCarSizePrice()
	{
		return $this->car_size_price ?: 0 ;
	}
	public function getTotalFineAmount():float 
	{
		return $this->total_fines ?: 0 ;
	}
	public function getTotalFineAmountFormatted():string
	{
		return number_format($this->getTotalFineAmount());
	}
	public function getOperationalFees():float 
	{
		return $this->operational_fees ?: 0 ;
	}
	public function getOperationalFeesFormatted():string
	{
		return number_format($this->getOperationalFees());
	}
	public function getCashFees():float 
	{
		return $this->cash_fees ?: 0 ;
	}
	public function getCashFeesFormatted():string
	{
		return number_format($this->getCashFees());
	}
	public function getDriverShare():float 
	{
		return $this->driver_share ?: 0 ;
	}
	public function getApplicationShare():float 
	{
		return $this->application_share ?: 0 ;
	}
	public function getTaxAmount():float 
	{
		return $this->tax_amount ?: 0 ;
	}
	public function getTaxAmountFormatted():string
	{
		return number_format($this->getTaxAmount());
	}
	public function getTotalPrice():float 
	{
		return (float)$this->total_price ?: 0 ;
	}
	public function getTotalPriceFormatted():string
	{
		return number_format($this->getTotalPrice());
	}
	
	public function getModelId()
	{
		return $this->model_id ;
	}
	public function getModelType()
	{
		return $this->model_type ;
	}
	public function getModelTypeFormatted(string $lang = null )
	{
		$lang = is_null($lang) ? getApiLang() : $lang ;
		return __($this->getModelType() , [] ,$lang  ) ;
	}
	public function getNoteFormatted()
	{
		return $this['note_'.getApiLang()];
	}
	public function user()
	{
		$modelType = $this->model_type;
		return $this->belongsTo('App\Models\\'.$modelType,'model_id','id');
	}
	/**
	 * * اسم العميل او السائق 
	 */
	public function getUserName(string $lang = null)
	{
		$lang = is_null($lang) ? getApiLang() : $lang ;
		return $this->user ? $this->user->getFullName($lang) : __('N/A');
	}	
}
