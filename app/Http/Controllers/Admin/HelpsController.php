<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelpRequest;
use App\Models\Help;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class HelpsController extends Controller
{
	use Globals;
	
	public function __construct()
	{
		foreach(['view'=>['index'] , 'create'=>['create','store'],'update'=>['edit','update'],'delete'=>['destroy']] as $name => $method ){
            $this->middleware('permission:'.getPermissionName($name) , ['only'=>$method]) ;
        }
		
	}
   

    public function index()
    {


        $models = Help::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.helps.index', [
			'models'=>$models,
			'pageTitle'=>__('Helps'),
			'createRoute'=>route('helps.create'),
			'editRouteName'=>'helps.edit',
			'deleteRouteName'=>'helps.destroy',
			'toggleIsActiveRoute'=>route('helps.toggle.is.active')
		]);
    }

    public function create()
    {
        return view('admin.helps.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'helps'=>[
				'title'=>__('Helps') ,
				'route'=>route('helps.index'),
			],
			'create-help'=>[
				'title'=>__('Create :page',['page'=>__('Help')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Helps'),
			'route'=>$model ? route('helps.update',['help'=>$model->id]) : route('helps.store') ,
			'model'=>$model ,
			'indexRoute'=>route('helps.index'),
			'helpModelTypesFormatted'=>[
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

    public function store(StoreHelpRequest $request)
    {
        $model = new Help();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Help',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Help',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('helps.index'),route('helps.create'));
    }

    public function edit(Help $help)
    {
        return view('admin.helps.crud',$this->getViewUrl($help),
	);
    }

    public function update(StoreHelpRequest $request, Help $help)
    {
		$help->syncFromRequest($request);
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Help',[],'en') .' [ ' . $help->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Help',[],'ar') .' [ ' . $help->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('helps.index'),route('helps.edit',['help'=>$help->id]));
    }

    public function destroy(Request $request,Help $help)
    {
		$help->delete();
		
		Notification::storeNewNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Help',[],'en') .' [ ' . $help->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Help',[],'ar') .' [ ' . $help->getName('ar') . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsActive(Request $request)
    {
        $model = Help::find($request->id);
		if($model){
			$model->toggleIsActive();
			
			Notification::storeNewNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Help',[],'en') .' [ ' . $model->getName('en') . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Help',[],'ar') .' [ ' . $model->getName('ar') . ' ]' ,
			);
			
		}
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
    }

}
