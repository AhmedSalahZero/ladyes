<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

   
    public function boot()
    {
		// collection macros
		
		Carbon::setWeekStartsAt(Carbon::FRIDAY);
		Carbon::setWeekEndsAt(Carbon::THURSDAY);

       
    }
}
