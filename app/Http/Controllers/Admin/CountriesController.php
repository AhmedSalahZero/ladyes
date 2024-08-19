<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateCountriesRequest;
use App\Models\Country;
use App\Models\Notification;
use App\Traits\Controllers\Globals;

class CountriesController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
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
    
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Countries'),
            'model' => $model,
            'indexRoute' => route('countries.index')
        ];
    }
	
	public function update(updateCountriesRequest $request, Country $country)
    {
       
		foreach($request->get('fees',[]) as $columnName => $value){
			$country->update([
				$columnName=>$value 
			]);
		}
        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Country Information', [], 'en') . ' [ ' . $country->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Country Information', [], 'ar') . ' [ ' . $country->getName('ar') . ' ]',
        );
		return response()->json([
			'status'=>true ,
			'message'=>__(':modelName Has Been Updated Successfully',['modelName'=>__('Country')]),
			'reloadCurrentPage'=>true
		]);
        // return $this->getWebRedirectRoute($request, route('car-sizes.index'), route('car-sizes.edit', [ 'car_size'=> $carSize->id]));
    }
	
}
