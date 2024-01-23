<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
	use Globals;
	
	public function __construct()
	{
		$this->middleware('permission:'.getPermissionName('view') , ['only'=>['index']]) ;
		$this->middleware('permission:'.getPermissionName('create') , ['only'=>['create','store']]) ;
		$this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
		$this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
		
	}
    public function index()
    {
        $models = City::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.cities.index', [
			'models'=>$models,
			'pageTitle'=>__('Cities'),
			'createRoute'=>route('cities.create'),
			'editRouteName'=>'cities.edit',
			'deleteRouteName'=>'cities.destroy'
		]);
    }

    public function create()
    {
        return view('admin.cities.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'cities'=>[
				'title'=>__('Cities') ,
				'route'=>route('cities.index'),
			],
			'create-cities'=>[
				'title'=>__('Create :page',['page'=>__('City')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Cities'),
			'route'=>$model ? route('cities.update',['city'=>$model->id]) : route('cities.store') ,
			'model'=>$model ,
			'indexRoute'=>route('cities.index'),
			'countriesFormattedForSelect'=>Country::get()->formattedForSelect(true,'getId','getName')
		];
	}

    public function store(StoreCityRequest $request)
    {
        $model = new City();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('City', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('City', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );
        return $this->getWebRedirectRoute($request,route('cities.index'),route('cities.create'));
    }

    public function edit(City $city)
    {
        return view('admin.cities.crud',$this->getViewUrl($city),
	);
    }

    public function update(StoreCityRequest $request, City $city)
    {
			$city->syncFromRequest($request);
			
			Notification::storeNewNotification(
				__('New Update', [], 'en'),
				__('New Update', [], 'ar'),
				$request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('City', [], 'en') . ' [ ' . $city->getName('en') . ' ]',
				$request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('City', [], 'ar') . ' [ ' . $city->getName('ar') . ' ]',
			);
			
			return $this->getWebRedirectRoute($request,route('cities.index'),route('cities.edit',['city'=>$city->id]));
    }

    public function destroy(Request $request,City $city)
    {
		$city->delete();
		Notification::storeNewNotification(
			__('New Deletion', [], 'en'),
			__('New Deletion', [], 'ar'),
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en') . __('City', [], 'en') . ' [ ' . $city->getName('en') . ' ]',
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar') . __('City', [], 'ar') . ' [ ' . $city->getName('ar') . ' ]',
		);
		
		return redirect()->back()->with('success',__('This Record Has Been Deleted Successfully'));
    }

  

}
