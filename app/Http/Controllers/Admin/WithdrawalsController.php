<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Http\Requests\ValidAmountRequest;
use App\Http\Requests\ValidWithdrawalAmountRequest;
use App\Models\Notification;
use App\Models\Withdrawal;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class WithdrawalsController extends Controller
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


        $models = Withdrawal::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.withdrawals.index', [
			'models'=>$models,
			'pageTitle'=>__('Withdrawals'),
			'createRoute'=>route('withdrawals.create'),
			'editRouteName'=>'withdrawals.edit',
			'deleteRouteName'=>'withdrawals.destroy',
		]);
    }

    public function create()
    {
        return view('admin.withdrawals.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'withdrawals'=>[
				'title'=>__('Withdrawals') ,
				'route'=>route('withdrawals.index'),
			],
			'create-withdrawal'=>[
				'title'=>__('Create :page',['page'=>__('Withdrawal')]),
				'route'=>'#'
			]
		];
		$fullUserClassName = $model ? '\App\Models\\'.$model->getModelType() : null;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Withdrawals'),
			'route'=>$model ? route('withdrawals.update',['withdrawal'=>$model->id]) : route('withdrawals.store') ,
			'model'=>$model ,
			'indexRoute'=>route('withdrawals.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2(),
			'usersFormattedForSelect'=>$fullUserClassName ? $fullUserClassName::get()->formattedForSelect(true,'getId','getFullName') : [],
			'paymentMethodsFormattedForSelect'=>PaymentType::allFormattedForSelect2(),
		];
	}

    public function store(StoreWithdrawalRequest $request)
    {
        $model = new Withdrawal();
		$fullClassName = '\App\Models\\'.$request->get('model_type') ;
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$amount = $request->get('amount') ;
		$paymentMethod  = $request->get('payment_method');
		$model->storeForUser($user , $amount , $currencyNameEn , $currencyNameAr,$paymentMethod);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en'). ' ' . __('Withdrawal',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar'). ' ' . __('Withdrawal',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
		);
        return $this->getWebRedirectRoute($request,route('withdrawals.index'),route('withdrawals.create'));
    }
	
	/**
	 * * هنضيف من هنا الغرامة من البوب اب في الداش بورد في صفحه العميل او السائق
	 */
	public function storeFor(ValidWithdrawalAmountRequest $request){

		$fullClassName = '\App\Models\\'.$request->get('model_type');
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$withdrawalAmount = $request->get('amount');
		$paymentMethod = $request->get('payment_method');
		$messageEn = (new Withdrawal())->generateBasicNotificationMessage($withdrawalAmount,$currencyNameEn,'en');
		$messageAr = (new Withdrawal())->generateBasicNotificationMessage($withdrawalAmount,$currencyNameAr,'ar');
		$user->storeWithdrawal($withdrawalAmount,$messageEn,$messageAr,$paymentMethod);
		return redirect()->back()->with('success',__('Withdrawal Has Been Added Successfully'));
	}

    public function edit(Withdrawal $withdrawal,Request $request)
    {
		return view('admin.withdrawals.crud',$this->getViewUrl($withdrawal));
    }

    public function update(StoreWithdrawalRequest $request, Withdrawal $withdrawal)
    {
				$fullClassName = '\App\Models\\'.$request->get('model_type') ;
				$user = $fullClassName::find($request->get('model_id'));
				$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
				$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
				$amount = $request->get('amount') ;
				$paymentMethod = $request->get('payment_method');
				$modelId = $request->get('model_id');
				$modelType = $request->get('model_type');
				$withdrawal->deleteOldNotification();
				$withdrawal->transaction->update([
					'amount'=>$amount ,
					'model_id'=>$modelId ,
					'model_type'=>$modelType,
					'note_en'=>$withdrawal->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
					'note_ar'=>$withdrawal->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
				]);
				$withdrawal->update([
					'model_id'=> $modelId , 
					'model_type'=>$modelType,
					'amount'=>$amount ,
					'payment_method'=>$paymentMethod
				]);
				$withdrawal->resendBasicNotificationMessage();
				
				Notification::storeNewAdminNotification(
					__('New Update',[],'en'),
					__('New Update',[],'ar'),
					$request->user('admin')->getName() .' '.__('Has Updated',[],'en'). ' ' . __('Withdrawal',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
					$request->user('admin')->getName() .' '.__('Has Updated',[],'ar'). ' ' . __('Withdrawal',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
				);
			return $this->getWebRedirectRoute($request,route('withdrawals.index'),route('withdrawals.edit',['withdrawal'=>$withdrawal->id]));
    }

    public function destroy(Request $request,Withdrawal $withdrawal)
    {
		$withdrawal->deleteOldNotification();
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en'). ' ' . __('Withdrawal',[],'en') .' [ # ' . $withdrawal->id . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar'). ' ' . __('Withdrawal',[],'ar') .' [ # ' . $withdrawal->id . ' ]' ,
		);
		$withdrawal->transaction ? $withdrawal->transaction->delete() : null;
		
		$withdrawal->delete();
		return $this->getWebDeleteRedirectRoute();
    }


}
