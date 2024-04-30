<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Interfaces\IHaveDeposit;
use App\Interfaces\ITransactionType;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasUpdatableNotification;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * الايداعات
 */
class Deposit extends Model implements ITransactionType
{
    use IsBaseModel,HasDefaultOrderScope , HasFactory , HasUpdatableNotification;
	
	protected $guarded = [
		'id'
	];
	public function client()
	{
		return $this->belongsTo(Client::class , 'model_id','id')->where('model_type','Client');
	}
	public function driver()
	{
		return $this->belongsTo(Client::class , 'model_id','id')->where('model_type','Driver');
	}

	
	 public function transactions()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::DEPOSIT);
	 }
	 public function generateBasicNotificationMessage(float $amount ,string $currencyNameFormatted, string $lang  ):string
	 {
		return __('Amount Has Been Added To Your Wallet :amount :currency', ['amount' => number_format($amount), 'currency' => $currencyNameFormatted],$lang);
	 }
	 
	  /**
	  * * هنا بنبعت ايداع للسائق بنصيبة من الغاء العميل لرحلة معينة
	   * * حساب النسبة اللي السائق بياخدها من الغرامة والباقي بيروح للتطبيق
	   * 
	   */
	 
	   public function storeNewForDriverAsTravelCompleted(Travel $travel):Deposit
	   {
			$totalAmount = $travel->calculateDriverShare();
		  $currencyNameEn = $travel->getCurrencyNameFormatted('en');
		  $currencyNameAr = $travel->getCurrencyNameFormatted('ar');
		  $travelId = $travel->id ;
		  if(!$travel->driver){
			  return $this ; 
		  }
		  $driver = $travel->driver ;
		  $driverId = $driver->id;
		
		  $deposit = Deposit::create([
			  'model_type'=>'Driver',
			  'payment_method'=>$travel->getPaymentMethod(),
			  'model_id'=>$driverId ,
			  'is_profit'=>true ,
			  'amount'=>$totalAmount,
			  'note_en' => $depositMessageEn = __('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($totalAmount), 'currency' => $currencyNameEn,'travelId'=>$travelId], 'en'),
			  'note_ar' => $depositMessageAr =__('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($totalAmount), 'currency' => $currencyNameAr,'travelId'=>$travelId], 'ar'),
		  ]);
		  $deposit->transactions()->create([
			  'type'=>TransactionType::DEPOSIT,
			  'is_profit'=>true ,
			  'amount'=>$totalAmount ,
			  'model_id'=>$driverId ,
			  'model_type'=>'Driver',
			  'note_en'=>$depositMessageEn ,
			  'note_ar'=>$depositMessageAr
		  ]);
		  $driver->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $depositMessageEn, $depositMessageAr, AppNotificationType::DEPOSIT,$deposit->id);
		  return $deposit ; 
	   }
	   
	 /**
	  * * هنا بنبعت ايداع للسائق بنصيبة من الغاء العميل لرحلة معينة
	   * * حساب النسبة اللي السائق بياخدها من الغرامة والباقي بيروح للتطبيق
	   * 
	   */
	 
	 public function storeNewForDriverAsTravelCancelled(Travel $travel,float $totalFeesAmount):Deposit
	 {
		$currencyNameEn = $travel->getCurrencyNameFormatted('en');
		$currencyNameAr = $travel->getCurrencyNameFormatted('ar');
		$travelId = $travel->id ;
		if(!$travel->driver){
			return $this ; 
		}
		$driver = $travel->driver ;
		$driverId = $driver->id;
		/**
		 * * حساب نصيب السائق
		 */
		$operationFees = $travel->getOperationalFees();
		$deductionPercentage = $driver->getDeductionPercentage();
		$driverDepositAmount = $deductionPercentage / 100 * $totalFeesAmount + $operationFees ; 
		
		$deposit = Deposit::create([
			'model_type'=>'Driver',
			'payment_method'=>$travel->getPaymentMethod(),
			'model_id'=>$driverId ,
			'amount'=>$driverDepositAmount,
            'note_en' => $depositMessageEn = __('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($driverDepositAmount), 'currency' => $currencyNameEn,'travelId'=>$travelId], 'en'),
            'note_ar' => $depositMessageAr =__('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($driverDepositAmount), 'currency' => $currencyNameAr,'travelId'=>$travelId], 'ar'),
		]);
		$deposit->transactions()->create([
			'type'=>TransactionType::DEPOSIT,
			'is_profit'=>true ,
			'amount'=>$driverDepositAmount ,
			'model_id'=>$driverId ,
			'model_type'=>'Driver',
			'note_en'=>$depositMessageEn ,
			'note_ar'=>$depositMessageAr
		]);
		$driver->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $depositMessageEn, $depositMessageAr, AppNotificationType::DEPOSIT,$deposit->id);
		return $deposit ; 
	 }
	 
	 public function getPaymentMethod():string 
	 {
		 return $this->payment_method ;
	 }
	 public function getPaymentMethodFormatted():string 
	 {
		 return __($this->getPaymentMethod() , [] , getApiLang()) ;
	 }
	 public function storeForUser(IHaveDeposit $user , float $amount , string $currencyNameEn , string $currencyNameAr  , string $paymentMethod)
	 {
		/**
		 * @var Client|Driver $user 
		 */
		$deposit = Deposit::create([
			'model_type'=> HHelpers::getClassNameWithoutNameSpace($user),
			'payment_method'=>$paymentMethod,
			'model_id'=>$user->id ,
			'amount'=>$amount,
            'note_en' => $depositMessageEn = $this->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
            'note_ar' => $depositMessageAr =$this->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
		 ]);
		 
		 $deposit->transaction()->create([
            'type' => TransactionType::DEPOSIT,
            'type_id' => $deposit->id,
            'model_id' => $user->id,
            'amount' => $amount,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($user),
            'note_en' => $depositMessageEn,
            'note_ar' => $depositMessageAr,
        ]);
		$user->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $depositMessageEn, $depositMessageAr, AppNotificationType::DEPOSIT,$deposit->id);
		
	 }
	 public function transaction()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::DEPOSIT);
	 }
	 
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
