<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Models\Notification;
use App\Models\Promotion;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class PromotionsController extends Controller
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


        $models = Promotion::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.promotions.index', [
			'models'=>$models,
			'pageTitle'=>__('Promotions'),
			'createRoute'=>route('promotions.create'),
			'editRouteName'=>'promotions.edit',
			'deleteRouteName'=>'promotions.destroy'
		]);
    }

    public function create()
    {
        return view('admin.promotions.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'promotions'=>[
				'title'=>__('Promotions') ,
				'route'=>route('promotions.index'),
			],
			'create-promotion'=>[
				'title'=>__('Create :page',['page'=>__('Promotion')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Promotions'),
			'route'=>$model ? route('promotions.update',['promotion'=>$model->id]) : route('promotions.store') ,
			'model'=>$model ,
			'indexRoute'=>route('promotions.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2()
		];
	}

    public function store(StorePromotionRequest $request)
    {
        $model = new Promotion();
		$model->syncFromRequest($request);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Promotion',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Promotion',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('promotions.index'),route('promotions.create'));
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.crud',$this->getViewUrl($promotion),
	);
    }

    public function update(StorePromotionRequest $request, Promotion $promotion)
    {
		$promotion->syncFromRequest($request);
			
			Notification::storeNewAdminNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Promotion',[],'en') .' [ ' . $promotion->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Promotion',[],'ar') .' [ ' . $promotion->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('promotions.index'),route('promotions.edit',['promotion'=>$promotion->id]));
    }

    public function destroy(Request $request,Promotion $promotion)
    {
		$promotion->delete();
		
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Promotion',[],'en') .' [ ' . $promotion->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Promotion',[],'ar') .' [ ' . $promotion->getName('ar') . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }

   

}
