<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Interfaces\IHaveWithdrawal;
use App\Interfaces\ITransactionType;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasUpdatableNotification;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * السحوبات
 */
class Withdrawal extends Model implements ITransactionType
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
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::WITHDRAWAL);
	 }
	 public function generateBasicNotificationMessage(float $amount ,string $currencyNameFormatted, string $lang  ):string
	 {
		return __('Amount Has Been Withdrew From Your Wallet :amount :currency', ['amount' => number_format($amount), 'currency' => $currencyNameFormatted],$lang);
	 }
	 
	 public function getPaymentMethod():string 
	 {
		 return $this->payment_method ;
	 }
	 public function getPaymentMethodFormatted():string 
	 {
		 return __($this->getPaymentMethod() , [] , getApiLang()) ;
	 }
	 public function storeForUser(IHaveWithdrawal $user , float $amount , string $currencyNameEn , string $currencyNameAr  , string $paymentMethod)
	 {
		/**
		 * @var Client|Driver $user 
		 */
		$withdrawal = Withdrawal::create([
			'model_type'=> HHelpers::getClassNameWithoutNameSpace($user),
			'payment_method'=>$paymentMethod,
			'model_id'=>$user->id ,
			'amount'=>$amount ,
            'note_en' => $withdrawalMessageEn = $this->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
            'note_ar' => $withdrawalMessageAr =$this->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
		 ]);
		 
		 $withdrawal->transaction()->create([
            'type' => TransactionType::WITHDRAWAL,
            'type_id' => $withdrawal->id,
            'model_id' => $user->id,
            'amount' => $amount * -1 ,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($user),
            'note_en' => $withdrawalMessageEn,
            'note_ar' => $withdrawalMessageAr,
        ]);
		$user->sendAppNotification(__('Withdrawal', [], 'en'), __('Withdrawal', [], 'ar'), $withdrawalMessageEn, $withdrawalMessageAr, AppNotificationType::WITHDRAWAL,$withdrawal->id);
		
	 }
	 public function transaction()
	 {
		return $this->hasOne(Transaction::class,'type_id','id')->where('type',TransactionType::WITHDRAWAL);
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
