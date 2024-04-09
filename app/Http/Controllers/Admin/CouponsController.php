<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class CouponsController extends Controller
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


        $models = Coupon::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.coupons.index', [
			'models'=>$models,
			'pageTitle'=>__('Coupons'),
			'createRoute'=>route('coupons.create'),
			'editRouteName'=>'coupons.edit',
			'deleteRouteName'=>'coupons.destroy',
		]);
    }

    public function create()
    {
        return view('admin.coupons.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'coupons'=>[
				'title'=>__('Coupons') ,
				'route'=>route('coupons.index'),
			],
			'create-coupon'=>[
				'title'=>__('Create :page',['page'=>__('Coupon')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Coupons'),
			'route'=>$model ? route('coupons.update',['coupon'=>$model->id]) : route('coupons.store') ,
			'model'=>$model ,
			'indexRoute'=>route('coupons.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2()
		];
	}

    public function store(StoreCouponRequest $request)
    {
        $model = new Coupon();
		$model->syncFromRequest($request);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en'). ' ' . __('Coupon',[],'en') .' [ ' . $model->getName() . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar'). ' ' . __('Coupon',[],'ar') .' [ ' . $model->getName() . ' ]' ,
		);
        return $this->getWebRedirectRoute($request,route('coupons.index'),route('coupons.create'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.crud',$this->getViewUrl($coupon),
	);
    }

    public function update(StoreCouponRequest $request, Coupon $coupon)
    {
		$coupon->syncFromRequest($request);
			
			Notification::storeNewAdminNotification(
				__('New Update',[],'en'),
				__('New Update',[],'ar'),
				$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . ' ' . __('Coupon',[],'en') .' [ ' . $coupon->getName() . ' ]' ,
				$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . ' ' . __('Coupon',[],'ar') .' [ ' . $coupon->getName() . ' ]' ,
			);
			
			return $this->getWebRedirectRoute($request,route('coupons.index'),route('coupons.edit',['coupon'=>$coupon->id]));
    }

    public function destroy(Request $request,Coupon $coupon)
    {
		$coupon->delete();
		
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en'). ' ' . __('Coupon',[],'en') .' [ ' . $coupon->getName('en') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar'). ' ' . __('Coupon',[],'ar') .' [ ' . $coupon->getName('ar') . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }


}
