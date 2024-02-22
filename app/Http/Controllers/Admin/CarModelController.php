<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarModelRequest;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CarModelController extends Controller
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
        $models = CarModel::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.car-models.index', [
            'models' => $models,
            'pageTitle' => __('Car Models'),
            'createRoute' => route('car-models.create'),
            'editRouteName' => 'car-models.edit',
            'deleteRouteName' => 'car-models.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.car-models.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'car-models' => [
                'title' => __('Car Models'),
                'route' => route('car-models.index'),
            ],
            'create-car-models' => [
                'title' => __('Create :page', ['page' => __('Car Model')]),
                'route' => '#'
            ]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Car Models'),
            'route' => $model ? route('car-models.update', ['car_model' => $model->id]) : route('car-models.store'),
            'model' => $model,
            'indexRoute' => route('car-models.index'),
            'carMakesFormattedForSelect' => CarMake::get()->formattedForSelect(true, 'getId', 'getName')
        ];
    }

    public function store(StoreCarModelRequest $request)
    {
        $model = new CarModel();
        $model->syncFromRequest($request);

        Notification::storeNewAdminNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('Car Make', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('Car Make', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-models.index'), route('car-models.create'));
    }

    public function edit(CarModel $car_model)
    {
        return view(
            'admin.car-models.crud',
            $this->getViewUrl($car_model),
        );
    }

    public function update(StoreCarModelRequest $request, CarModel $carModel)
    {
        $carModel->syncFromRequest($request);

        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Car Model', [], 'en') . ' [ ' . $carModel->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Car Model', [], 'ar') . ' [ ' . $carModel->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-models.index'), route('car-models.edit', ['car_model' => $carModel->id]));
    }

    public function destroy(Request $request, CarModel $car_model)
    {
        $car_model->delete();

        Notification::storeNewAdminNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en') . __('Car Model', [], 'en') . ' [ ' . $car_model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar') . __('Car Model', [], 'ar') . ' [ ' . $car_model->getName('ar') . ' ]',
        );
		return $this->getWebDeleteRedirectRoute();
    }
}
