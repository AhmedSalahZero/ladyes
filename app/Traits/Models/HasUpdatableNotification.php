<?php 
namespace App\Traits\Models;

use App\Helpers\HHelpers;
use App\Models\Notification;

trait HasUpdatableNotification 
{
	public function deleteOldNotification( )
	{
		Notification::where('data->title_en',getTypeWithoutNamespace($this))->where('data->model_id',$this->id)->delete();
	}
	public function resendBasicNotificationMessage():self
	{
		$messageEn = $this->generateBasicNotificationMessage($this->getAmount() , $this->user->getCountry()->getCurrencyFormatted('en'),  'en');
		$messageAr = $this->generateBasicNotificationMessage($this->getAmount() , $this->user->getCountry()->getCurrencyFormatted('ar') ,  'ar');
		$this->user->sendAppNotification(__('Deposit', [], 'en'), __('Deposit', [], 'ar'), $messageEn, $messageAr , strtolower(HHelpers::getClassNameWithoutNameSpace($this)),$this->id);
		return $this ; 
	}
}
