<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\ValidAmountRequest;
use App\Http\Requests\ValidPaymentAmountRequest;
use App\Models\Notification;
use App\Models\Payment;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class PaymentsController extends Controller
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


        $models = Payment::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.payments.index', [
			'models'=>$models,
			'pageTitle'=>__('Payments'),
			'createRoute'=>route('payments.create'),
			'editRouteName'=>'payments.edit',
			'deleteRouteName'=>'payments.destroy',
		]);
    }

    public function create()
    {
        return view('admin.payments.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'payments'=>[
				'title'=>__('Payments') ,
				'route'=>route('payments.index'),
			],
			'create-payment'=>[
				'title'=>__('Create :page',['page'=>__('Payment')]),
				'route'=>'#'
			]
		];
		$fullUserClassName = $model ? '\App\Models\\'.$model->getModelType() : null;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Payments'),
			'route'=>$model ? route('payments.update',['payment'=>$model->id]) : route('payments.store') ,
			'model'=>$model ,
			'indexRoute'=>route('payments.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2(),
			'usersFormattedForSelect'=>$fullUserClassName ? $fullUserClassName::get()->formattedForSelect(true,'getId','getFullName') : [],
			'paymentMethodsFormattedForSelect'=>PaymentType::allFormattedForSelect2(),
		];
	}

    public function store(StorePaymentRequest $request)
    {
        $model = new Payment();
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
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en'). ' ' . __('Payment',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar'). ' ' . __('Payment',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
		);
        return $this->getWebRedirectRoute($request,route('payments.index'),route('payments.create'));
    }
	
	/**
	 * * هنضيف من هنا الغرامة من البوب اب في الداش بورد في صفحه العميل او السائق
	 */
	public function storeFor(ValidPaymentAmountRequest $request){

		$fullClassName = '\App\Models\\'.$request->get('model_type');
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$paymentAmount = $request->get('amount');
		$paymentMethod = $request->get('payment_method');
		$messageEn = (new Payment())->generateBasicNotificationMessage($paymentAmount,$currencyNameEn,'en');
		$messageAr = (new Payment())->generateBasicNotificationMessage($paymentAmount,$currencyNameAr,'ar');
		$user->storePayment($paymentAmount,$messageEn,$messageAr,$paymentMethod);
		return redirect()->back()->with('success',__('Payment Has Been Added Successfully'));
	}

    public function edit(Payment $payment,Request $request)
    {
		return view('admin.payments.crud',$this->getViewUrl($payment));
    }

    public function update(StorePaymentRequest $request, Payment $payment)
    {
				$fullClassName = '\App\Models\\'.$request->get('model_type') ;
				$user = $fullClassName::find($request->get('model_id'));
				$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
				$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
				$amount = $request->get('amount') ;
				$paymentMethod = $request->get('payment_method');
				$modelId = $request->get('model_id');
				$modelType = $request->get('model_type');
				$payment->deleteOldNotification();
				$payment->transaction->update([
					'amount'=>$amount ,
					'model_id'=>$modelId ,
					'model_type'=>$modelType,
					'note_en'=>$payment->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
					'note_ar'=>$payment->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
				]);
				$payment->update([
					'model_id'=> $modelId , 
					'model_type'=>$modelType,
					'amount'=>$amount ,
					'payment_method'=>$paymentMethod
				]);
				$payment->resendBasicNotificationMessage();
				
				Notification::storeNewAdminNotification(
					__('New Update',[],'en'),
					__('New Update',[],'ar'),
					$request->user('admin')->getName() .' '.__('Has Updated',[],'en'). ' ' . __('Payment',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
					$request->user('admin')->getName() .' '.__('Has Updated',[],'ar'). ' ' . __('Payment',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
				);
			return $this->getWebRedirectRoute($request,route('payments.index'),route('payments.edit',['payment'=>$payment->id]));
    }

    public function destroy(Request $request,Payment $payment)
    {
		$payment->deleteOldNotification();
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en'). ' ' . __('Payment',[],'en') .' [ # ' . $payment->id . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar'). ' ' . __('Payment',[],'ar') .' [ # ' . $payment->id . ' ]' ,
		);
		$payment->transaction ? $payment->transaction->delete() : null;
		
		$payment->delete();
		return $this->getWebDeleteRedirectRoute();
    }


}
