<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Interfaces\IHaveFine;
use App\Interfaces\ITransactionType;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasUpdatableNotification;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * الغرمات
 */
class Fine extends Model implements ITransactionType
{
    use IsBaseModel ,HasFactory, HasDefaultOrderScope,HasUpdatableNotification;
	
	protected $guarded = [
		'id'
	];
	/**
	 * * العميل او السائق المطبق عليه الغرامة
	 */
	public function user()
	{
		$modelType = $this->model_type;
		return $this->belongsTo('App\Models\\'.$modelType,'model_id','id');
	}
	/**
	 * * اسم العميل او السائق المطبق عليه الغرامة
	 */
	public function getUserName(string $lang = null)
	{
		$lang = is_null($lang) ? getApiLang() : $lang ;
		return $this->user ? $this->user->getFullName($lang) : __('N/A');
	}
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
	public function getAmountFormatted():string
	{
		return number_format($this->getAmount());
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
	/**
	 * * تسجيل غرامة جديدة علي العميل علي رحلة معينة
	 * * اما بسبب التاخير علي بداية العميل للرحلة او بسبب الغائة الرحلة
	 * * ثم نقوم باعلام العميل عن طريق ارسال اشعار لة
	 */
	public function storeForTravel(Travel $travel , float $fineFeesAmount, string $fineNoteEn , string $fineNoteAr ):Fine
	{
		if($fineFeesAmount <= 0 ){
			return  $this ;
		}
		// $currencyNameEn = $travel->getCurrencyNameFormatted('en');
        // $currencyNameAr = $travel->getCurrencyNameFormatted('ar');
		$fine = Fine::create([
            'travel_id' => $travel->id ,
            'model_id' => $travel->getClientId(),
            'model_type' => 'Client',
            'amount' => $fineFeesAmount,
            'is_paid' => false ,
            'note_en' => $fineNoteEn,
            'note_ar' => $fineNoteAr 
        ]);	
		
        $travel->client->sendAppNotification(__('Fine', [], 'en'), __('Fine', [], 'ar'), $fineNoteEn, $fineNoteAr, AppNotificationType::FINE,$fine->id);
		
		return $fine ;
	}

	
	
	/**
	 * * دي لما بنضيف غرامة جديدة من الداش بورد وبالتالي هنا مفيش رحلة 
	 */
	public function storeForUser(IHaveFine $user , float $fineAmount , string $currencyNameEn , string $currencyNameAr):self
	{
		/**
		 * @var Client $user
		 */
		$user->storeFine($fineAmount,$currencyNameEn  , $currencyNameAr);
		return $this 	;
	}
	
	/**
	 * * هل تم تسديد الغرامة ام لا
	 */
	public function isPaid():bool
	{
		return (bool)$this->is_paid;
	}
	public function getIsPaidFormatted():string 
	{
		return $this->isPaid() ? __('Yes' , [] , getApiLang()) : __('No',[],getApiLang());
	}
	public function getNoteFormatted()
	{
		return $this['note_'.getApiLang()];
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
	 public function addNewPaidTransaction(float $fineFeesAmount  ):Fine 
	 {
		 /**
		  * * هنحسب قيمة الغرامة
		  */
		  $currencyNameEn = $this->travel->getCurrencyNameFormatted('en') ;
		  $currencyNameAr =    $this->travel->getCurrencyNameFormatted('ar') ;
		  
		$this->transaction()->create([
			'type'=>TransactionType::FINE,
			'amount' => $fineFeesAmount * -1  , 
			'model_id'=>$this->getModelId(),
			'model_type'=>'Client',
			'note_en'=>$fineNoteEn = $this->generateBasicNotificationMessage($fineFeesAmount , $currencyNameEn,'en'),
			'note_ar'=>$fineNoteAr = $this->generateBasicNotificationMessage($fineFeesAmount , $currencyNameAr,'ar'),
		]);
		$this->markAsPaid();
        $this->client->sendAppNotification(__('Fine', [], 'en'), __('Fine', [], 'ar'), $fineNoteEn, $fineNoteAr, AppNotificationType::FINE,$this->id);
		
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
		$deposit->storeNewForDriverAsTravelFees($travel,$fineFeesAmount);
		return $this ;
	}
	/**
	 * * هنقوم بتسديد الغرامة علي الرحلة
	 * * واعطاء نصيب كل من السائق والتطبيق
	 */
	public function settlementTravelFee(float $fineFeesAmount  ):Fine
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
	public function generateBasicNotificationMessage(float $amount ,string $currencyNameFormatted, string $lang  ):string
	{
		return __('The Fine Amount :amount :currency Has Been Successfully Paid',['amount'=>number_format($amount),'currency'=>$currencyNameFormatted],'en');		
	}

}
