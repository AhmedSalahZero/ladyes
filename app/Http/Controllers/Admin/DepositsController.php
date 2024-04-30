<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\ValidAmountRequest;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\Driver;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class DepositsController extends Controller
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


        $models = Deposit::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.deposits.index', [
			'models'=>$models,
			'pageTitle'=>__('Deposits'),
			'createRoute'=>route('deposits.create'),
			'editRouteName'=>'deposits.edit',
			'deleteRouteName'=>'deposits.destroy',
		]);
    }

    public function create()
    {
        return view('admin.deposits.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'deposits'=>[
				'title'=>__('Deposits') ,
				'route'=>route('deposits.index'),
			],
			'create-deposit'=>[
				'title'=>__('Create :page',['page'=>__('Deposit')]),
				'route'=>'#'
			]
		];
		$fullUserClassName = $model ? '\App\Models\\'.$model->getModelType() : null;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Deposits'),
			'route'=>$model ? route('deposits.update',['deposit'=>$model->id]) : route('deposits.store') ,
			'model'=>$model ,
			'indexRoute'=>route('deposits.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2(),
			'usersFormattedForSelect'=>$fullUserClassName ? $fullUserClassName::get()->formattedForSelect(true,'getId','getFullName') : [],
			'paymentMethodsFormattedForSelect'=>PaymentType::allFormattedForSelect2(),
		];
	}

    public function store(StoreDepositRequest $request)
    {
        $model = new Deposit();
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
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en'). ' ' . __('Deposit',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar'). ' ' . __('Deposit',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
		);
        return $this->getWebRedirectRoute($request,route('deposits.index'),route('deposits.create'));
    }
	
	/**
	 * * هنضيف من هنا الايداع من البوب اب في الداش بورد في صفحه العميل او السائق
	 */
	public function storeFor(ValidAmountRequest $request){
		/**
		 * @var Client|Driver $user
		 */
		$fullClassName = '\App\Models\\'.$request->get('model_type');
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$depositAmount = $request->get('amount');
		$paymentMethod = $request->get('payment_method');
		$messageEn = (new Deposit())->generateBasicNotificationMessage($depositAmount,$currencyNameEn,'en');
		$messageAr = (new Deposit())->generateBasicNotificationMessage($depositAmount,$currencyNameAr,'ar');
		$user->storeDeposit($depositAmount,$messageEn,$messageAr,$paymentMethod);
		return redirect()->back()->with('success',__('Deposit Has Been Added Successfully'));
	}

    public function edit(Deposit $deposit,Request $request)
    {
		return view('admin.deposits.crud',$this->getViewUrl($deposit));
    }

    public function update(StoreDepositRequest $request, Deposit $deposit)
    {
				$fullClassName = '\App\Models\\'.$request->get('model_type') ;
				$user = $fullClassName::find($request->get('model_id'));
				$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
				$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
				$amount = $request->get('amount') ;
				$paymentMethod = $request->get('payment_method');
				$modelId = $request->get('model_id');
				$modelType = $request->get('model_type');
				$deposit->deleteOldNotification();
				$deposit->transaction->update([
					'amount'=>$amount ,
					'model_id'=>$modelId ,
					'model_type'=>$modelType,
					'note_en'=>$deposit->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
					'note_ar'=>$deposit->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
				]);
				$deposit->update([
					'model_id'=> $modelId , 
					'model_type'=>$modelType,
					'amount'=>$amount ,
					'payment_method'=>$paymentMethod
				]);
				$deposit->resendBasicNotificationMessage();
				
				
				
				Notification::storeNewAdminNotification(
					__('New Update',[],'en'),
					__('New Update',[],'ar'),
					$request->user('admin')->getName() .' '.__('Has Updated',[],'en'). ' ' . __('Deposit',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
					$request->user('admin')->getName() .' '.__('Has Updated',[],'ar'). ' ' . __('Deposit',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
				);
			return $this->getWebRedirectRoute($request,route('deposits.index'),route('deposits.edit',['deposit'=>$deposit->id]));
    }

    public function destroy(Request $request,Deposit $deposit)
    {
		$deposit->deleteOldNotification();
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en'). ' ' . __('Deposit',[],'en') .' [ # ' . $deposit->id . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar'). ' ' . __('Deposit',[],'ar') .' [ # ' . $deposit->id . ' ]' ,
		);
		$deposit->transaction ? $deposit->transaction->delete() : null;
		
		$deposit->delete();
		return $this->getWebDeleteRedirectRoute();
    }


}
