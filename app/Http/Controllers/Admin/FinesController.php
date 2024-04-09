<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFineRequest;
use App\Http\Requests\ValidAmountRequest;
use App\Http\Requests\ValidFineAmountRequest;
use App\Models\Fine;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class FinesController extends Controller
{
	use Globals;
	
	public function __construct()
	{
		foreach(['view'=>['index'] , 'create'=>['create','store','store-for'],'update'=>['edit','update'],'delete'=>['destroy']] as $name => $method ){
            $this->middleware('permission:'.getPermissionName($name) , ['only'=>$method]) ;
        }
	}
   

    public function index()
    {


        $models = Fine::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.fines.index', [
			'models'=>$models,
			'pageTitle'=>__('Fines'),
			'createRoute'=>route('fines.create'),
			'editRouteName'=>'fines.edit',
			'deleteRouteName'=>'fines.destroy',
		]);
    }

    public function create()
    {
        return view('admin.fines.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'fines'=>[
				'title'=>__('Fines') ,
				'route'=>route('fines.index'),
			],
			'create-fine'=>[
				'title'=>__('Create :page',['page'=>__('Fine')]),
				'route'=>'#'
			]
		];
		$fullUserClassName = $model ? '\App\Models\\'.$model->getModelType() : null;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Fines'),
			'route'=>$model ? route('fines.update',['fine'=>$model->id]) : route('fines.store') ,
			'model'=>$model ,
			'indexRoute'=>route('fines.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2(),
			'usersFormattedForSelect'=>$fullUserClassName ? $fullUserClassName::get()->formattedForSelect(true,'getId','getFullName') : [],
		];
	}

    public function store(StoreFineRequest $request)
    {
        $model = new Fine();
		// $model->syncFromRequest($request);
		$fullClassName = '\App\Models\\'.$request->get('model_type') ;
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$amount = $request->get('amount') ;
		$model->storeForUser($user , $amount , $currencyNameEn , $currencyNameAr);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Applied New',[],'en') . __('Fine',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
			$request->user('admin')->getName() .' '.__('Has Applied New',[],'ar') . __('Fine',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
		);
        return $this->getWebRedirectRoute($request,route('fines.index'),route('fines.create'));
    }
	/**
	 * * هنضيف من هنا الغرامة من البوب اب في الداش بورد في صفحه العميل او السائق
	 */
	public function storeFor(ValidAmountRequest $request){
		$fullClassName = '\App\Models\\'.$request->get('model_type');
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$fineAmount = $request->get('amount');
		$user->storeFine($fineAmount,$currencyNameEn,$currencyNameAr);
		return redirect()->back()->with('success',__('Fine Has Been Added Successfully'));
	}

    public function edit(Fine $fine,Request $request)
    {
		if(!$fine->isPaid()){
			return view('admin.fines.crud',$this->getViewUrl($fine));
		}
		return redirect()->back();
		// return $this->getWebRedirectRoute($request,route('fines.index'),route('fines.edit',['fine'=>$fine->id]));
		
    }

    public function update(StoreFineRequest $request, Fine $fine)
    {
			if(!$fine->isPaid()){
				$fullClassName = '\App\Models\\'.$request->get('model_type') ;
				$user = $fullClassName::find($request->get('model_id'));
				$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
				$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
				$amount = $request->get('amount') ;
				$modelId = $request->get('model_id');
				$modelType = $request->get('model_type');
				$fine->deleteOldNotification();
				$fine->transaction->update([
					'amount'=>$amount ,
					'model_id'=>$modelId ,
					'model_type'=>$modelType,
					'note_en'=>$fine->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
					'note_ar'=>$fine->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
				]);
				
				$fine->update([
					'model_id'=>$modelId , 
					'model_type'=>$modelType,
					'amount'=>$amount 
				]);
				$fine->resendBasicNotificationMessage();
				Notification::storeNewAdminNotification(
					__('New Update',[],'en'),
					__('New Update',[],'ar'),
					$request->user('admin')->getName() .' '.__('Has Updated',[],'en'). ' ' . __('Fine',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
					$request->user('admin')->getName() .' '.__('Has Updated',[],'ar'). ' ' . __('Fine',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
				);
			}
			return $this->getWebRedirectRoute($request,route('fines.index'),route('fines.edit',['fine'=>$fine->id]));
    }

    public function destroy(Request $request,Fine $fine)
    {
		$fine->deleteOldNotification();
		$fine->transaction ? $fine->transaction->delete() : null;
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en'). ' ' . __('Fine',[],'en') .' [ # ' . $fine->id . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar'). ' ' . __('Fine',[],'ar') .' [ # ' . $fine->id . ' ]' ,
		);
		
		$fine->delete();
		return $this->getWebDeleteRedirectRoute();
    }


}
