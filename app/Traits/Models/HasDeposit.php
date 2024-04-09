<?php

namespace App\Traits\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Models\Deposit;


trait HasDeposit
{
	public function deposits()
    {
        return $this->hasMany(Deposit::class, 'model_id', 'id');
    } 
    public function storeDeposit(float $amount, string $noteEn  , string $noteAr , string $paymentMethod): Deposit
    {
       $deposit = Deposit::create([
            'model_id' => $this->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($this),
			'payment_method'=>$paymentMethod ,
            'amount' => $amount,
            'note_en' => $noteEn,
            'note_ar' => $noteAr 
        ]);
        $this->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $noteEn, $noteAr, AppNotificationType::DEPOSIT,$deposit->id);
		$deposit->transaction()->create([
			'amount'=>$amount,
			'type'=>TransactionType::DEPOSIT ,
			'type_id'=>$deposit->id ,
			'model_id'=>$this->id,
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($this),
			'note_en'=>$noteEn,
			'note_ar'=>$noteAr,
		]);
        return $deposit ;
    }
}
