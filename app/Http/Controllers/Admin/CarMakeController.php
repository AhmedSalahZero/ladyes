<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarMakeRequest;
use App\Models\CarMake;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CarMakeController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('create'), ['only' => ['create', 'store']]) ;
        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit', 'update']]) ;
        $this->middleware('permission:' . getPermissionName('delete'), ['only' => ['destroy']]) ;
    }

    public function index()
    {
        $models = CarMake::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.car-makes.index', [
            'models' => $models,
            'pageTitle' => __('Car Makes'),
            'createRoute' => route('car-makes.create'),
            'editRouteName' => 'car-makes.edit',
            'deleteRouteName' => 'car-makes.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.car-makes.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'car-makes' => [
                'title' => __('Car Makes'),
                'route' => route('car-makes.index'),
            ],
            'create-car-makes' => [
                'title' => __('Create :page', ['page' => __('Car Make')]),
                'route' => '#'
            ]
        ];
        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Car Makes'),
            'route' => $model ? route('car-makes.update', ['car_make' => $model->id]) : route('car-makes.store'),
            'model' => $model,
            'indexRoute' => route('car-makes.index')
        ];
    }

    public function store(StoreCarMakeRequest $request)
    {
        $model = new CarMake();
        $model->syncFromRequest($request);

        Notification::storeNewAdminNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en'). ' ' . __('Car Make', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar'). ' ' . __('Car Make', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-makes.index'), route('car-makes.create'));
    }

    public function edit(CarMake $car_make)
    {
        return view(
            'admin.car-makes.crud',
            $this->getViewUrl($car_make),
        );
    }

    public function update(StoreCarMakeRequest $request, CarMake $carMake)
    {
        $carMake->syncFromRequest($request);
        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Car Make', [], 'en') . ' [ ' . $carMake->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Car Make', [], 'ar') . ' [ ' . $carMake->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-makes.index'), route('car-makes.edit', ['car_make' => $carMake->id]));
    }

    public function destroy(Request $request, CarMake $car_make)
    {
        $car_make->delete();

        Notification::storeNewAdminNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en'). ' ' . __('Car Make', [], 'en') . ' [ ' . $car_make->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar'). ' ' . __('Car Make', [], 'ar') . ' [ ' . $car_make->getName('ar') . ' ]',
        );
		return $this->getWebDeleteRedirectRoute();
    }
}
