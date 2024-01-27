<?php

namespace App\Providers;

use App\Events\TestEvent;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Admin;
use App\Models\AdminNotifacation;
use App\Models\City;
use App\Models\Country;
use App\Models\Driver;
use App\Notifications\Admins\AdminNotification;
use App\Rules\ValidPhoneNumberRule;
use App\Services\PhoneNumberService;
use App\Settings\ServiceSettings;
use Illuminate\Foundation\Auth\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
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
