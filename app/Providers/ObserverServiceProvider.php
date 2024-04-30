<?php

namespace App\Providers;

use App\Models\Driver;
use App\Observers\DriverObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
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
		Driver::observe(DriverObserver::class);
    }
}
