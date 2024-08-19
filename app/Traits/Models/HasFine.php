<?php

namespace App\Traits\Models;

use App\Enum\AppNotificationType;
use App\Helpers\HHelpers;
use App\Models\Bonus;
use App\Models\Fine;

trait HasFine
{
    public function hasFines(): bool
    {
        return $this->getUnpaidFines() > 0;
    }

    public function fines()
    {
        return $this->hasMany(Fine::class, 'model_id', 'id');
    }

    public function getUnpaidFines()
    {
        return $this->fines->where('is_paid', 0);
    }

    public function getPaidFines()
    {
        return $this->fines->where('is_paid', 1);
    }

    public function getTotalAmountOfUnpaid(): float
    {
        return $this->getUnpaidFines()->sum('amount');
    }

    public function storeFine(float $fineFeesAmount, string $currencyNameEn, string $currencyNameAr): self
    {
        $fine = Fine::create([
            // 'travel_id' => $travel->id ,
            'model_id' => $this->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($this),
            'amount' => $fineFeesAmount,
            'is_paid' => false,
            'note_en' => $fineNoteEn = __('You Have :amount :currency Fine In Your Wallet', ['amount' => $fineFeesAmount, 'currency' => $currencyNameEn], 'en'),
            'note_ar' => $fineNoteAr = __('You Have :amount :currency Fine In Your Wallet', ['amount' => $fineFeesAmount, 'currency' => $currencyNameAr], 'ar')
        ]);
        $this->sendAppNotification(__('Fine', [], 'en'), __('Fine', [], 'ar'), $fineNoteEn, $fineNoteAr, AppNotificationType::FINE,$fine->id);
        return $this ;
    }

   
}
