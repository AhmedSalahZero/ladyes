<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Traits\Controllers\Globals;

class TravelsController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index','showCancelled']]) ;
        // $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
    }

    public function index()
    {
        $models = Travel::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.travels.index', [
            'models' => $models,
            'pageTitle' => __('Travels')
        ]);
    }
	public function showCancelled()
    {
        $models = Travel::defaultOrdered()
		->onlyCancelled()
		->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.travels.cancelled', [
            'models' => $models,
            'pageTitle' => __('Cancelled Travels')
        ]);
    }
	public function showOnTheWay()
    {
        $models = Travel::defaultOrdered()
		->onlyOnTheWay()
		->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.travels.on-the-way', [
            'models' => $models,
            'pageTitle' => __('On The Way Travels')
        ]);
    }
	public function showCompleted()
    {
        $models = Travel::defaultOrdered()
		->onlyCompleted()
		->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.travels.completed', [
            'models' => $models,
            'pageTitle' => __('Completed Travels')
        ]);
    }
    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'travels' => [
                'title' => __('Travels'),
                'route' => route('travels.index'),
            ],
           
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Travels'),
            'model' => $model,
            'indexRoute' => route('travels.index')
        ];
    }
}
