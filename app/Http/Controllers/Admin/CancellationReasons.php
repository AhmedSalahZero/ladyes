<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CancellationReasons extends Controller
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


        $models = TravelCondition::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.travel-conditions.index', [
			'models'=>$models,
			'pageTitle'=>__('Travel Conditions'),
			'createRoute'=>route('travel-conditions.create'),
			'editRouteName'=>'travel-conditions.edit',
			'deleteRouteName'=>'travel-conditions.destroy',
			'toggleIsActiveRoute'=>route('travel-conditions.toggle.is.active')
		]);
    }

    public function create()
    {
        return view('admin.travel-conditions.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'travel-conditions'=>[
				'title'=>__('Travel Conditions') ,
				'route'=>route('travel-conditions.index'),
			],
			'create-travel-condition'=>[
				'title'=>__('Create :page',['page'=>__('Travel Condition')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Travel Conditions'),
			'route'=>$model ? route('travel-conditions.update',['travel_condition'=>$model->id]) : route('travel-conditions.store') ,
			'model'=>$model ,
			'indexRoute'=>route('travel-conditions.index')
		];
	}

    public function store(StoreTravelConditionRequest $request)
    {
        $model = new TravelCondition();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Travel Condition',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Travel Condition',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('travel-conditions.index'),route('travel-conditions.create'));
    }

    public function edit(TravelCondition $travel_condition)
    {
        return view('admin.travel-conditions.crud',$this->getViewUrl($travel_condition),
	);
    }

    public function update(StoreTravelConditionRequest $request, TravelCondition $travel_condition)
    {
		$travel_condition->syncFromRequest($request);
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Travel Condition',[],'en') .' [ ' . $travel_condition->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Travel Condition',[],'ar') .' [ ' . $travel_condition->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('travel-conditions.index'),route('travel-conditions.edit',['travel_condition'=>$travel_condition->id]));
    }

    public function destroy(Request $request,TravelCondition $travel_condition)
    {
		$travel_condition->delete();
		
		Notification::storeNewNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Travel Condition',[],'en') .' [ ' . $travel_condition->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Travel Condition',[],'ar') .' [ ' . $travel_condition->getName('ar') . ' ]' ,
		);
		
		return redirect()->back()->with('success',__('This Record Has Been Deleted Successfully'));
    }

    public function toggleIsActive(Request $request)
    {
        $model = TravelCondition::find($request->id);
		if($model){
			$model->toggleIsActive();
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Travel Condition',[],'en') .' [ ' . $model->getName('en') . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Travel Condition',[],'ar') .' [ ' . $model->getName('ar') . ' ]' ,
			);
			
		}
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
    }

}
