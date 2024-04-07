<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Traits\Accessors\IsBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * لو هنضيف بونص للعميل وليكن مثلا لانه اتم اول رحلة بنجاح
 */
class Bonus extends Model
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
	 * * هنضيف بونص للعميل في حالة اتم اول رحلة بنجاح 
	 */
	public function storeForFirstTravel(Travel $travel ):self
	{
		if(!$travel->client ){
			return $this ;
		}
		$isFirstTravel = $travel->client->isFirstTravel();
		$bonusAmount = $travel->calculateFirstTravelBonus($isFirstTravel);
	
		if(!$isFirstTravel || $bonusAmount <= 0){
			return $this ;
		}
		$currencyNameEn = $travel->getCurrencyNameFormatted('en');
        $currencyNameAr = $travel->getCurrencyNameFormatted('ar');
		
		$bonus = Bonus::create([
            'travel_id' => $travel->id ,
            'model_id' => $travel->getClientId(),
            'model_type' => 'Client',
            'amount' => $bonusAmount,
            'note_en' => $bonusNoteEn = __('You Have :amount :currency Bonus In Your Wallet For Completing Your First Travel #:travelId', ['amount' => $bonusAmount, 'currency' => $currencyNameEn, 'travelId' => $travel->id], 'en'),
            'note_ar' => $bonusNoteAr = __('You Have :amount :currency Bonus In Your Wallet For Completing Your First Travel #:travelId', ['amount' => $bonusAmount, 'currency' => $currencyNameAr, 'travelId' => $travel->id], 'ar')
        ]);	
		
		$bonus->transaction()->create([
			'amount'=>$bonusAmount,
			'type'=>TransactionType::BONUS ,
			'type_id'=>$bonus->id ,
			'model_id'=>$travel->client->id ,
			'model_type'=>'Client',
			'note_en'=>$bonusNoteEn,
			'note_ar'=>$bonusNoteAr,
		]);
		
        $travel->client->sendAppNotification(__('Bonus', [], 'en'), __('Bonus', [], 'ar'), $bonusNoteEn, $bonusNoteAr, AppNotificationType::BONUS);
		
		return $bonus ;
	}
	

	 public function transaction()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::BONUS);
	 }
	
}
