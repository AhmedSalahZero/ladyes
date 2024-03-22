<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarSizeRequest;
use App\Http\Requests\updateCarSizePricesRequest;
use App\Models\CarSize;
use App\Models\Country;
use App\Models\Notification;
use App\Traits\Controllers\Globals;

class CarSizesController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
    }

    public function index()
    {
        $models = CarSize::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.car-sizes.index', [
            'models' => $models,
            'pageTitle' => __('Car Sizes'),
            // 'createRoute'=>route('car-makes.create'),
            'editRouteName'=>'car-sizes.edit',
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
                'title' => __('Car Sizes'),
                'route' => route('car-sizes.index'),
            ],
    
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Car Sizes'),
            'model' => $model,
            'indexRoute' => route('car-sizes.index'),
			'countriesFormatted'=>Country::get()->formattedForSelect(true,'getId','getName'),
            'route' => $model ? route('car-sizes.update', ['car_size' => $model->id]) : route('car-sizes.store'),
        ];
    }
	public function edit(CarSize $carSize)
    {
        return view('admin.car-sizes.crud',$this->getViewUrl($carSize),
	);
    }
	public function update(StoreCarSizeRequest $request, CarSize $carSize)
    {
        $carSize->storeBasicForm($request);
		
		foreach($request->get('country_ids',[]) as $countryId){
			$carSize->countryPrices()->syncWithoutDetaching([
				$countryId => [
					'model_type' => HHelpers::getClassNameWithoutNameSpace($carSize),
				]
			]);
		}
		

        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Car Size', [], 'en') . ' [ ' . $carSize->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Car Size', [], 'ar') . ' [ ' . $carSize->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-sizes.index'), route('car-sizes.edit', [ 'car_size'=> $carSize->id]));
    }
	
	public function updatePrices(updateCarSizePricesRequest $request, CarSize $carSize)
    {
       
		foreach($request->get('prices',[]) as $countryId => $price){
			$carSize->countryPrices()->syncWithoutDetaching([
				$countryId => [
					'price' => $price ,
				]
			]);
		}
        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Car Size', [], 'en') . ' [ ' . $carSize->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Car Size', [], 'ar') . ' [ ' . $carSize->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('car-sizes.index'), route('car-sizes.edit', [ 'car_size'=> $carSize->id]));
    }
	
}
