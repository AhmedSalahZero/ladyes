<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Traits\Accessors\IsBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * الايداعات
 */
class Deposit extends Model
{
    use IsBaseModel ;
    use HasFactory ;
	
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

	public function getModelId()
	{
		return $this->model_id ;
	}
	 public function transactions()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::DEPOSIT);
	 }
	 /**
	  * * هنا بنبعت ايداع للسائق بنصيبة من الغاء العميل لرحلة معينة
	  */
	  /**
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
		// ////////////
		
		$deposit = Deposit::create([
			'model_type'=>'Driver',
			'model_id'=>$driverId ,
			'amount'=>$driverDepositAmount,
            'note_en' => $depositMessageEn = __('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($driverDepositAmount), 'currency' => $currencyNameEn,'travelId'=>$travelId], 'en'),
            'note_ar' => $depositMessageAr =__('Amount Has Been Added To Your Wallet :amount :currency For Travel Number :travelId', ['amount' => number_format($driverDepositAmount), 'currency' => $currencyNameAr,'travelId'=>$travelId], 'ar'),
		]);
		$deposit->transactions()->create([
			'type'=>TransactionType::DEPOSIT,
			'amount'=>$driverDepositAmount ,
			'model_id'=>$driverId ,
			'model_type'=>'Driver',
			'note_en'=>$depositMessageEn ,
			'note_ar'=>$depositMessageAr
		]);
		$driver->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $depositMessageEn, $depositMessageAr, AppNotificationType::DEPOSIT);
		return $deposit ; 
	 }
}
