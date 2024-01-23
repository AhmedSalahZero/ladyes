<?php

namespace App\Providers;

use App\Events\TestEvent;
use App\Models\Admin;
use App\Models\AdminNotifacation;
use App\Models\Country;
use App\Models\Driver;
use App\Notifications\Admins\AdminNotification;
use App\Rules\ValidPhoneNumberRule;
use App\Services\PhoneNumberService;
use App\Settings\ServiceSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		// TestEvent::dispatch('test');
		// dd('good');
		// $admin = Admin::first();
		// dd($admin->notify(new AdminNotification('comment')));
		// $setting = new ServiceSettings();
		// dd($setting->TEST_KEY);
		// $validPhoneService = new PhoneNumberService();
		// $numberFormatted = $validPhoneService->formatNumber('+1025894984','eg');
		// dd($numberFormatted);
		Paginator::useBootstrap();
		if(true){
			app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
			app()->make(PermissionRegistrar::class)->clearClassPermissions();
			$permissions = getPermissions();
			foreach($permissions as $permission){
				try{
					Permission::findByName($permission['name'],'admin');
				}
				catch(\Exception $e){
					
					$permission = Permission::create(array_merge(Arr::only($permission,'name'),['guard_name'=>'admin']));
					foreach(Admin::all() as $admin){
						$admin->givePermissionTo($permission);
					}
				}
			}	
		}
    }
}
