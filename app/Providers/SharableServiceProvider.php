<?php

namespace App\Providers;

use App\Settings\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SharableServiceProvider extends ServiceProvider
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
		View::composer('*', function( $view){
			$view->with('user', getCurrentUser());
		});
		View::composer('*', function($view){
			$view->with('lang', app()->getLocale());
			$view->with('logo',App(SiteSetting::class)->getLogo());
			$view->with('favIcon',App(SiteSetting::class)->getFavIcon());
		});
    }
}
