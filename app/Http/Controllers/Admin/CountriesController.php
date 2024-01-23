<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Traits\Controllers\Globals;

class CountriesController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        // $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
    }

    public function index()
    {
        $models = Country::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.countries.index', [
            'models' => $models,
            'pageTitle' => __('Countries'),
            // 'createRoute'=>route('car-makes.create'),
            // 'editRouteName'=>'car-makes.edit',
            // 'deleteRouteName'=>'car-makes.destroy'
        ]);
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'countries' => [
                'title' => __('Countries'),
                'route' => route('countries.index'),
            ],
            // 'create-car-makes'=>[
            // 	'title'=>__('Create :page',['page'=>__('Car Make')]),
            // 	'route'=>'#'
            // ]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Countries'),
            // 'route'=>$model ? route('car-makesc.update',['car_make'=>$model->id]) : route('countries.store') ,
            'model' => $model,
            'indexRoute' => route('countries.index')
        ];
    }
}
