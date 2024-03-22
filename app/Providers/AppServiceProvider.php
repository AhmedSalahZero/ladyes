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
        // $driver = Driver::find(1);
        // $driver->location = new Point(31.128770312685667,30.9209548721609);
        // $driver->save();
        // dd('good');

        // ->orderBy('distance','desc')->

        // $latitude =  30.033333 ;
        // $longitude =  31.233334 ;
        // $rangeDistanceInKm  = 700 ;
        // $drivers  = ;
        // $geoService = new GeoService();
        // $lat1 = 31.111560297574997 ;
        // $long1 = 30.935951102171803 ;
        // $lat2 = 31.129353547472142   ;
        // $long2 = 31.19991344726501;

        // $distance = $geoService->getDistanceBetweenInKm($lat1,$long1 , $lat2,$long2);
        // dd(round($distance,2));
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
