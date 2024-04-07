<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Traits\Accessors\IsBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * الغرمات
 */
class Fine extends Model
{
    use IsBaseModel ;
    use HasFactory ;
	
	protected $guarded = [
		'id'
	];
	public function client()
	{
		return $this->belongsTo(Client::class , 'model_id','id');
	}
	public function driver()
	{
		return $this->belongsTo(Driver::class , 'model_id','id');
	}
	public function travel()
	{
		return $this->belongsTo(Travel::class,'travel_id','id');
	}
	/**
	 * * مقدار الغرامة المطبقة
	 */
	public function getAmount():float 
	{
		return $this->amount ?: 0 ;
	}
	public function getModelId()
	{
		return $this->model_id ;
	}
	/**
	 * * تسجيل غرامة جديدة علي العميل علي رحلة معينة
	 * * ثم نقوم باعلام العميل عن طريق ارسال اشعار لة
	 */
	public function storeForTravel(Travel $travel , float $fineFeesAmount = null ):Fine
	{
		$fineFeesAmount = is_null($fineFeesAmount) ? $travel->calculateCancellationFees() : $fineFeesAmount ;
		$currencyNameEn = $travel->getCurrencyNameFormatted('en');
        $currencyNameAr = $travel->getCurrencyNameFormatted('ar');
		$fine = Fine::create([
            'travel_id' => $travel->id ,
            'model_id' => $travel->getClientId(),
            'model_type' => 'Client',
            'amount' => $fineFeesAmount,
            'is_paid' => false ,
            'note_en' => $fineNoteEn = __('You Have :amount :currency Fine In Your Wallet For Cancellation Travel #:travelId', ['amount' => $fineFeesAmount, 'currency' => $currencyNameEn, 'travelId' => $travel->id], 'en'),
            'note_ar' => $fineNoteAr = __('You Have :amount :currency Fine In Your Wallet For Cancellation Travel #:travelId', ['amount' => $fineFeesAmount, 'currency' => $currencyNameAr, 'travelId' => $travel->id], 'ar')
        ]);	
		
        $travel->client->sendAppNotification(__('Fine', [], 'en'), __('Fine', [], 'ar'), $fineNoteEn, $fineNoteAr, AppNotificationType::FINE);
		
		return $fine ;
	}
	
	/**
	 * * هل تم تسديد الغرامة ام لا
	 */
	public function isPaid():bool
	{
		return (bool)$this->is_paid;
	}
	
	/**
	 * * هنحدد ان الغرامة تم تسديدها
	 */
	
	 public function markAsPaid():Fine
	 {
		$this->is_paid = true ;
		$this->save();
		return $this ;
	 }
	 public function transaction()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::FINE);
	 }
	 /**
	  * * هنا بنسدد الغرامة الموجودة بالفعل ولكن لم تسدد بعد بشرط وجود مبلغ كافي في محفظة العميل
		* * هنضيف حوالة بالسالب في محفظتة لتسديد الغرامة ونبعتله اشعار ان تم تسديد هذة الغرامة
	 */
	 public function addNewPaidTransaction(float $fineFeesAmount = null ):Fine 
	 {
		 /**
		  * * هنحسب قيمة الغرامة
		  */
		  $fineFeesAmount = is_null($fineFeesAmount) ? $this->travel->calculateCancellationFees() : $fineFeesAmount ;
		  $currencyNameEn = $this->travel->getCurrencyNameFormatted('en');
		  $currencyNameAr = $this->travel->getCurrencyNameFormatted('ar');
		  
		$this->transaction()->create([
			'type'=>TransactionType::FINE,
			'amount' => $fineFeesAmount * -1  , 
			'model_id'=>$this->getModelId(),
			'model_type'=>'Client',
			'note_en'=>$fineNoteEn = __('The Fine Amount :amount :currency Has Been Successfully Paid',['amount'=>number_format($fineFeesAmount),'currency'=>$currencyNameEn],'en'),
			'note_ar'=>$fineNoteAr = __('The Fine Amount :amount :currency Has Been Successfully Paid',['amount'=>number_format($fineFeesAmount),'currency'=>$currencyNameAr],'ar'),
		]);
		$this->markAsPaid();
        $this->client->sendAppNotification(__('Fine', [], 'en'), __('Fine', [], 'ar'), $fineNoteEn, $fineNoteAr, AppNotificationType::FINE);
		
		/**
		 * * هنحول نسبة العميل و نسبة السائق
		 */
		$this->giveDriverAndAppTheirShare($fineFeesAmount);
		
		return $this ;
	 }
	public function giveDriverAndAppTheirShare(float $fineFeesAmount):Fine
	{
		$deposit = new Deposit();
		$travel = $this->travel ;
		if(!$travel){
			return $this ;
		}
		$deposit->storeNewForDriverAsTravelCancelled($travel,$fineFeesAmount);
		return $this ;
	}
	/**
	 * * هنقوم بتسديد الغرامة علي الرحلة
	 * * واعطاء نصيب كل من السائق والتطبيق
	 */
	public function settlementTravelFee(float $fineFeesAmount = null ):Fine
	{
		/**
		 * * لو تم تسديد الغرامة بالفعل مش هنعمل حاجه
		 */
		
		 $fineIsPaid = $this->isPaid();
		 if($fineIsPaid){
			return $this ;
		 }
		/**
		 * * هنحسب قيمة الغرامة
		 */
		$fineFeesAmount = is_null($fineFeesAmount) ? $this->travel->calculateCancellationFees() : $fineFeesAmount ;
		$hasBalanceInHisWallet =  $this->client->getTotalWalletBalance() >= $fineFeesAmount;
		/**
		 * @var Fine $fine 
		 */
		/**
		 * * لو العميل معاه فلوس كافيه في المحفظة هنسدد مبلغ الغرامة عن طريق اضافة قيمة بالسالب
		 */
		if($hasBalanceInHisWallet){
			/**
			 * * هنضيف حوالة بالسالب في محفظتة لتسديد الغرامة
			 */
			$this->addNewPaidTransaction($fineFeesAmount);
			
		}

	
		return $this ;
	}
}
