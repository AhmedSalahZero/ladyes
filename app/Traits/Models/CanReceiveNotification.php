<?php 
namespace App\Traits\Models;


trait CanReceiveNotification 
{
	 /**
	  * * هل يقدر يستلم اشعارات ولا لا
     */
    public function getCanReceiveNotification()
    {
        return (bool) $this->can_receive_notifications ;
    }

    public function getCanReceiveNotificationFormatted()
    {
        $canReceiveNotification = $this->getCanReceiveNotification();

        return $canReceiveNotification ? __('Yes') : __('No');
    }
	
	
}
