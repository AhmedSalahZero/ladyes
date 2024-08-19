<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBonusRequest;
use App\Http\Requests\ValidAmountRequest;
use App\Models\Bonus;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class BonusesController extends Controller
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


        $models = Bonus::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
		
        return view('admin.bonuses.index', [
			'models'=>$models,
			'pageTitle'=>__('Bonuses'),
			'createRoute'=>route('bonuses.create'),
			'editRouteName'=>'bonuses.edit',
			'deleteRouteName'=>'bonuses.destroy',
		]);
    }

    public function create()
    {
        return view('admin.bonuses.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'bonuses'=>[
				'title'=>__('Bonuses') ,
				'route'=>route('bonuses.index'),
			],
			'create-bonus'=>[
				'title'=>__('Create :page',['page'=>__('Bonus')]),
				'route'=>'#'
			]
		];
		$fullUserClassName = $model ? '\App\Models\\'.$model->getModelType() : null;
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Bonuses'),
			'route'=>$model ? route('bonuses.update',['bonus'=>$model->id]) : route('bonuses.store') ,
			'model'=>$model ,
			'indexRoute'=>route('bonuses.index'),
			'discountTypesFormatted'=>DiscountType::allFormattedForSelect2(),
			'usersFormattedForSelect'=>$fullUserClassName ? $fullUserClassName::get()->formattedForSelect(true,'getId','getFullName') : [],
		];
	}

    public function store(StoreBonusRequest $request)
    {
        $model = new Bonus();
		$fullClassName = '\App\Models\\'.$request->get('model_type') ;
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$amount = $request->get('amount') ;
		$model->storeForUser($user , $amount , $currencyNameEn , $currencyNameAr);
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Applied New',[],'en') . __('Bonus',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
			$request->user('admin')->getName() .' '.__('Has Applied New',[],'ar') . __('Bonus',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
		);
        return $this->getWebRedirectRoute($request,route('bonuses.index'),route('bonuses.create'));
    }
	
	/**
	 * * هنضيف من هنا الغرامة من البوب اب في الداش بورد في صفحه العميل او السائق
	 */
	public function storeFor(ValidAmountRequest $request){
		/**
		 * @var Driver|Client $user
		 */
		$fullClassName = '\App\Models\\'.$request->get('model_type');
		$user = $fullClassName::find($request->get('model_id'));
		$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
		$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
		$bonusAmount = $request->get('amount');
		$messageEn = (new Bonus())->generateBasicNotificationMessage($bonusAmount,$currencyNameEn,'en');
		$messageAr = (new Bonus())->generateBasicNotificationMessage($bonusAmount,$currencyNameAr,'ar');
		
		$user->storeBonus($bonusAmount,$messageEn,$messageAr);
		return redirect()->back()->with('success',__('Bonus Has Been Added Successfully'));
	}

    public function edit(Bonus $bonus,Request $request)
    {
		return view('admin.bonuses.crud',$this->getViewUrl($bonus));
    }

    public function update(StoreBonusRequest $request, Bonus $bonus)
    {
				$fullClassName = '\App\Models\\'.$request->get('model_type') ;
				$user = $fullClassName::find($request->get('model_id'));
				$currencyNameEn = $user->getCountry()->getCurrencyFormatted('en') ;
				$currencyNameAr = $user->getCountry()->getCurrencyFormatted('ar') ;
				$amount = $request->get('amount') ;
				$modelId = $request->get('model_id');
				$modelType = $request->get('model_type');
				$bonus->deleteOldNotification();
				$bonus->transaction->update([
					'amount'=>$amount ,
					'model_id'=>$modelId ,
					'model_type'=>$modelType,
					'note_en'=>$bonus->generateBasicNotificationMessage($amount,$currencyNameEn,'en'),
					'note_ar'=>$bonus->generateBasicNotificationMessage($amount,$currencyNameAr,'ar'),
				]);
				
				$bonus->update([
					'model_id'=>$modelId , 
					'model_type'=>$modelType,
					'amount'=>$amount 
				]);
				
				$bonus->resendBasicNotificationMessage();
			
				Notification::storeNewAdminNotification(
					__('New Update',[],'en'),
					__('New Update',[],'ar'),
					$request->user('admin')->getName() .' '.__('Has Updated',[],'en'). ' ' . __('Bonus',[],'en') .'  [ ' . $amount . ' ' . $currencyNameEn . ' ] ' . __('To',[],'en') . ' ' . $user->getFullName('en') ,
					$request->user('admin')->getName() .' '.__('Has Updated',[],'ar'). ' ' . __('Bonus',[],'ar') .'  [ ' . $amount . ' ' . $currencyNameAr . ' ] ' . __('To',[],'ar') . ' ' . $user->getFullName('ar') ,
				);
			return $this->getWebRedirectRoute($request,route('bonuses.index'),route('bonuses.edit',['bonus'=>$bonus->id]));
    }

    public function destroy(Request $request,Bonus $bonus)
    {
		$bonus->deleteOldNotification();
		
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en').' ' . __('Bonus',[],'en') .' [ # ' . $bonus->id . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar').' ' . __('Bonus',[],'ar') .' [ # ' . $bonus->id . ' ]' ,
		);
		$bonus->transaction ? $bonus->transaction->delete() : null;
		$bonus->delete();
		return $this->getWebDeleteRedirectRoute();
    }


}
