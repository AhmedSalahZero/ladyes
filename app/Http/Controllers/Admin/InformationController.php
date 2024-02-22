<?php

namespace App\Http\Controllers\Admin;

use App\Enum\InformationSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInformationRequest;
use App\Models\Information;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class InformationController extends Controller
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
        $models = Information::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.information.index', [
			'models'=>$models,
			'pageTitle'=>__('Information'),
			'createRoute'=>route('information.create'),
			'editRouteName'=>'information.edit',
			'deleteRouteName'=>'information.destroy',
			'toggleIsActiveRoute'=>route('information.toggle.is.active')
		]);
    }

    public function create()
    {
        return view('admin.information.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'information'=>[
				'title'=>__('Information') ,
				'route'=>route('information.index'),
			],
			'create-information'=>[
				'title'=>__('Create :page',['page'=>__('Information')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Information'),
			'route'=>$model ? route('information.update',['information'=>$model->id]) : route('information.store') ,
			'model'=>$model ,
			'indexRoute'=>route('information.index'),
			'informationSectionsFormatted'=>InformationSection::allFormattedForSelect2()
		];
	}

    public function store(StoreInformationRequest $request)
    {
        $model = new Information();
		$model->syncFromRequest($request);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Information',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Information',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('information.index'),route('information.create'));
    }

    public function edit(Information $information)
    {
        return view('admin.information.crud',$this->getViewUrl($information),
	);
    }

    public function update(StoreInformationRequest $request, Information $information)
    {
		$information->syncFromRequest($request);
			
			Notification::storeNewAdminNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Information',[],'en') .' [ ' . $information->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Information',[],'ar') .' [ ' . $information->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('information.index'),route('information.edit',['information'=>$information->id]));
    }

    public function destroy(Request $request,Information $information)
    {
		$information->delete();
		
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Information',[],'en') .' [ ' . $information->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Information',[],'ar') .' [ ' . $information->getName('ar') . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsActive(Request $request)
    {
        $model = Information::find($request->id);
		if($model){
			$model->toggleIsActive();
			
			Notification::storeNewAdminNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Information',[],'en') .' [ ' . $model->getName('en') . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Information',[],'ar') .' [ ' . $model->getName('ar') . ' ]' ,
			);
			
		}
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
    }

}
