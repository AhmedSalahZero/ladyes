<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCancellationReasonRequest;
use App\Models\CancellationReason;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CancellationReasonsController extends Controller
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


        $models = CancellationReason::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.cancellation-reasons.index', [
			'models'=>$models,
			'pageTitle'=>__('Cancellation Reasons'),
			'createRoute'=>route('cancellation-reasons.create'),
			'editRouteName'=>'cancellation-reasons.edit',
			'deleteRouteName'=>'cancellation-reasons.destroy',
			'toggleIsActiveRoute'=>route('cancellation-reasons.toggle.is.active')
		]);
    }

    public function create()
    {
        return view('admin.cancellation-reasons.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'cancellation-reasons'=>[
				'title'=>__('Cancellation Reasons') ,
				'route'=>route('cancellation-reasons.index'),
			],
			'create-cancellation-reasons'=>[
				'title'=>__('Create :page',['page'=>__('Cancellation Reason')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Cancellation Reasons'),
			'route'=>$model ? route('cancellation-reasons.update',['cancellation_reason'=>$model->id]) : route('cancellation-reasons.store') ,
			'model'=>$model ,
			'indexRoute'=>route('cancellation-reasons.index'),
			'cancellationReasonModelTypesFormatted'=>[
				[
					'title'=>__('Driver'),
					'value'=>'Driver'
				],
				[
					'title'=>__('Client'),
					'value'=>'Client'
				]
			]
		];
	}

    public function store(StoreCancellationReasonRequest $request)
    {
        $model = new CancellationReason();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Cancellation Reason',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Cancellation Reason',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('cancellation-reasons.index'),route('cancellation-reasons.create'));
    }

    public function edit(CancellationReason $cancellation_reason)
    {
        return view('admin.cancellation-reasons.crud',$this->getViewUrl($cancellation_reason),
	);
    }

    public function update(StoreCancellationReasonRequest $request, CancellationReason $cancellation_reason)
    {
		$cancellation_reason->syncFromRequest($request);
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Cancellation Reason',[],'en') .' [ ' . $cancellation_reason->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Cancellation Reason',[],'ar') .' [ ' . $cancellation_reason->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('cancellation-reasons.index'),route('cancellation-reasons.edit',['cancellation_reason'=>$cancellation_reason->id]));
    }

    public function destroy(Request $request,CancellationReason $cancellation_reason)
    {
		$cancellation_reason->delete();
		
		Notification::storeNewNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Cancellation Reason',[],'en') .' [ ' . $cancellation_reason->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Cancellation Reason',[],'ar') .' [ ' . $cancellation_reason->getName('ar') . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsActive(Request $request)
    {
        $model = CancellationReason::find($request->id);
		if($model){
			$model->toggleIsActive();
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Cancellation Reason',[],'en') .' [ ' . $model->getName('en') . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Cancellation Reason',[],'ar') .' [ ' . $model->getName('ar') . ' ]' ,
			);
			
		}
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
    }

}
