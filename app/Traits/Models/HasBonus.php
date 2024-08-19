<?php

namespace App\Traits\Models;

use App\Enum\AppNotificationType;
use App\Enum\TransactionType;
use App\Helpers\HHelpers;
use App\Models\Bonus;

trait HasBonus
{
	public function bonuses()
    {
        return $this->hasMany(Bonus::class, 'model_id', 'id');
    } 
    public function storeBonus(float $bonusAmount, string $bonusNoteEn  , string $bonusNoteAr): Bonus
    {
       $bonus = Bonus::create([
            'model_id' => $this->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($this),
            'amount' => $bonusAmount,
            'note_en' => $bonusNoteEn,
            'note_ar' => $bonusNoteAr 
        ]);
        $this->sendAppNotification(__('Bonus', [], 'en'), __('Bonus', [], 'ar'), $bonusNoteEn, $bonusNoteAr, AppNotificationType::BONUS,$bonus->id);
		$bonus->transaction()->create([
			'amount'=>$bonusAmount,
			'type'=>TransactionType::BONUS ,
			'type_id'=>$bonus->id ,
			'model_id'=>$this->id,
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($this),
			'note_en'=>$bonusNoteEn,
			'note_ar'=>$bonusNoteAr,
		]);
        return $bonus ;
    }
}
