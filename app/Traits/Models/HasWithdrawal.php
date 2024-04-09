<?php

namespace App\Traits\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Models\Withdrawal;


trait HasWithdrawal
{
	public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'model_id', 'id');
    } 
    public function storeWithdrawal(float $amount, string $noteEn  , string $noteAr , string $paymentMethod): Withdrawal
    {
       $withdrawal = Withdrawal::create([
            'model_id' => $this->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($this),
			'payment_method'=>$paymentMethod ,
            'amount' => $amount,
            'note_en' => $noteEn,
            'note_ar' => $noteAr 
        ]);
        $this->sendAppNotification(__('Withdrawal', [], 'en'), __('Withdrawal', [], 'ar'), $noteEn, $noteAr, AppNotificationType::WITHDRAWAL,$withdrawal->id);
		$withdrawal->transaction()->create([
			'amount'=>$amount * -1,
			'type'=>TransactionType::WITHDRAWAL ,
			'type_id'=>$withdrawal->id ,
			'model_id'=>$this->id,
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($this),
			'note_en'=>$noteEn,
			'note_ar'=>$noteAr,
		]);
        return $withdrawal ;
    }
}
