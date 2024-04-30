<?php

namespace App\Providers;

use App\Models\Admin;
use Exception;
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
		set_time_limit(0);
    
        Paginator::useBootstrap();
        if (true) {
            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
            app()->make(PermissionRegistrar::class)->clearClassPermissions();
            $permissions = getPermissions();
            foreach ($permissions as $permission) {
                try {
                    Permission::findByName($permission['name'], 'admin');
                } catch(Exception $e) {
                    try {
                        $permission = Permission::create(array_merge(Arr::only($permission, 'name'), ['guard_name' => 'admin']));
                        foreach (Admin::all() as $admin) {
                            $admin->givePermissionTo($permission);
                        }
                    } catch(Exception $e) {
                    }
                }
            }
        }
    }
}
