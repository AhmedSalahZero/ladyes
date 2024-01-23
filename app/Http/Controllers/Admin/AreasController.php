<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAreaRequest;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class AreasController extends Controller
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
        $models = Area::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.areas.index', [
			'models'=>$models,
			'pageTitle'=>__('Areas'),
			'createRoute'=>route('areas.create'),
			'editRouteName'=>'areas.edit',
			'deleteRouteName'=>'areas.destroy'
		]);
    }

    public function create()
    {
        return view('admin.areas.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'areas'=>[
				'title'=>__('Areas') ,
				'route'=>route('areas.index'),
			],
			'create-areas'=>[
				'title'=>__('Create :page',['page'=>__('Area')]),
				'route'=>'#'
			]
		];
		$selectedCountryId = $model ? $model->getCountryId() : 0 ;
		$selectedCityId = $model ? $model->getCityId() : 0 ;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Areas'),
			'route'=>$model ? route('areas.update',['area'=>$model->id]) : route('areas.store') ,
			'model'=>$model ,
			'indexRoute'=>route('areas.index'),
			'countriesFormattedForSelect'=>Country::get()->formattedForSelect(true,'getId','getName'),
			'citiesFormattedForSelect'=>City::where('country_id',$selectedCountryId)->get()->formattedForSelect(true,'getId','getName'),
			'areasFormattedForSelect'=>Area::where('city_id',$selectedCityId)->get()->formattedForSelect(true,'getId','getName'),
		];
	}

    public function store(StoreAreaRequest $request)
    {
        $model = new Area();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('Area', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('Area', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );
        return $this->getWebRedirectRoute($request,route('areas.index'),route('areas.create'));
    }

    public function edit(Area $area)
    {
        return view('admin.areas.crud',$this->getViewUrl($area),
	);
    }

    public function update(StoreAreaRequest $request, Area $area)
    {
			$area->syncFromRequest($request);
			
			Notification::storeNewNotification(
				__('New Update', [], 'en'),
				__('New Update', [], 'ar'),
				$request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('Area', [], 'en') . ' [ ' . $area->getName('en') . ' ]',
				$request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('Area', [], 'ar') . ' [ ' . $area->getName('ar') . ' ]',
			);
			
			
			return $this->getWebRedirectRoute($request,route('areas.index'),route('areas.edit',['area'=>$area->id]));
    }

    public function destroy(Request $request,Area $area)
    {
		$area->delete();
		
		Notification::storeNewNotification(
			__('New Deletion', [], 'en'),
			__('New Deletion', [], 'ar'),
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en') . __('Area', [], 'en') . ' [ ' . $area->getName('en') . ' ]',
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar') . __('Area', [], 'ar') . ' [ ' . $area->getName('ar') . ' ]',
		);
		
		return redirect()->back()->with('success',__('This Record Has Been Deleted Successfully'));
    }

  

}
