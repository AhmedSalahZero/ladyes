<?php

namespace App\Observers;

use App\Models\Driver;

class DriverObserver
{
   public static function saved(Driver $driver)
   {
	
		if($driver->isDirty('is_listing_to_orders_now')){
			$driver->handleConnectionLogs();
		}
   }
}
