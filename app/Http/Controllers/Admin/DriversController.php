<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\City;
use App\Models\Country;
use App\Models\Driver;
use App\Models\EmergencyContact;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class DriversController extends Controller
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
        $models = Driver::with(['sentInvitationCodes','receivedInvitationCodes'])->defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.drivers.index', [
			'models'=>$models,
			'pageTitle'=>__('Drivers'),
			'modelType'=>'Driver',
			'createRoute'=>route('drivers.create'),
			'editRouteName'=>'drivers.edit',
			'deleteRouteName'=>'drivers.destroy',
			'toggleIsVerifiedRoute'=>route('driver.toggle.is.verified'),
			'countriesFormattedForSelect'=>Country::get()->formattedForSelect(true,'getId','getName'),
			'emergencyContactsFormatted'=>EmergencyContact::get()->formattedForSelect(true,'getId','getName'),
			'toggleCanReceiveTravelInfos' => route('emergency-contacts.toggle.can.receive.travel.infos')
		]);
    }

    public function create()
    {
        return view('admin.drivers.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array
	{
		$selectedCountryId = $model ? $model->getCountryId() : 0 ;
		$selectedMakeId = $model ? $model->getMakeId() : 0 ;

		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'drivers'=>[
				'title'=>__('Drivers') ,
				'route'=>route('drivers.index'),
			],
			'create-driver'=>[
				'title'=>__('Create :page',['page'=>__('Driver')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Drivers'),
			'route'=>$model ? route('drivers.update',['driver'=>$model->id]) : route('drivers.store') ,
			'model'=>$model ,
			'indexRoute'=>route('drivers.index'),
			'countriesFormattedForSelect'=>Country::get()->formattedForSelect(true,'getId','getName'),
			'citiesFormattedForSelect'=>City::where('country_id',$selectedCountryId)->get()->formattedForSelect(true,'getId','getName'),
			'carMakesFormattedForSelect'=>CarMake::get()->formattedForSelect(true,'getId','getName'),
			'carModelsFormattedForSelect'=>CarModel::where('make_id',$selectedMakeId)->get()->formattedForSelect(true,'getId','getName'),
			'carSizesFormattedForSelect'=>CarSize::get()->formattedForSelect(true,'getId','getName'),
			'drivingRangeFormatted'=>Driver::getDefaultDrivingRangeFormatted()
		];
	}

    public function store(StoreDriverRequest $request)
    {
        $model = new Driver();
		$model->syncFromRequest($request);
		Notification::storeNewNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('Driver', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('Driver', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );
        return $this->getWebRedirectRoute($request,route('drivers.index'),route('drivers.create'));
    }

    public function edit(Driver $driver)
    {
        return view('admin.drivers.crud',$this->getViewUrl($driver),
	);
    }

    public function update(StoreDriverRequest $request, Driver $driver)
    {
			$driver->syncFromRequest($request);

			Notification::storeNewNotification(
				__('New Update', [], 'en'),
				__('New Update', [], 'ar'),
				$request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Driver', [], 'en') . ' [ ' . $driver->getName('en') . ' ]',
				$request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Driver', [], 'ar') . ' [ ' . $driver->getName('ar') . ' ]',
			);

			return $this->getWebRedirectRoute($request,route('drivers.index'),route('drivers.edit',['driver'=>$driver->id]));
    }

    public function destroy(Request $request,Driver $driver)
    {
		$driver->delete();
		Notification::storeNewNotification(
			__('New Deletion', [], 'en'),
			__('New Deletion', [], 'ar'),
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en') . __('Driver', [], 'en') . ' [ ' . $driver->getName('en') . ' ]',
			$request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar') . __('Driver', [], 'ar') . ' [ ' . $driver->getName('ar') . ' ]',
		);
		return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsBanned(Request $request)
    {
        $driver = Driver::find($request->id);
		if($driver){
			$banMessageEn = $driver->isBanned() ? __('Has Unbanned', [], 'en') : __('Has Banned', [], 'en') ;
			$banMessageAr = $driver->isBanned() ? __('Has Unbanned', [], 'ar') : __('Has Banned', [], 'ar') ;

			$driver->isBanned() ? $driver->unban() : $driver->ban([
				'comment'=>$request->get('comment')
				]) ;

			Notification::storeNewNotification(
				__('New Update', [], 'en'),
				__('New Update', [], 'ar'),
				$request->user('admin')->getName() . ' ' . $banMessageEn . __('Driver', [], 'en') . ' [ ' . $driver->getName('en') . ' ]',
				$request->user('admin')->getName() . ' ' . $banMessageAr . __('Driver', [], 'ar') . ' [ ' . $driver->getName('ar') . ' ]',
			);

		}
		return redirect()->back()->with('success',__('The Action Has Been Done'));
    }

	public function toggleIsVerified(Request $request)
    {
        $driver = Driver::find($request->id);
		if($driver){

			$verifiedMessageEn = $driver->getIsVerified() ? __('Has UnVerified', [], 'en') : __('Has Verified', [], 'en') ;
			$verifiedMessageAr = $driver->getIsVerified() ? __('Has UnVerified', [], 'ar') : __('Has Verified', [], 'ar') ;

			$driver->toggleIsVerified();

			Notification::storeNewNotification(
				__('New Update', [], 'en'),
				__('New Update', [], 'ar'),
				$request->user('admin')->getName() . ' ' . $verifiedMessageEn . __('Driver', [], 'en') . ' [ ' . $driver->getName('en') . ' ]',
				$request->user('admin')->getName() . ' ' . $verifiedMessageAr . __('Driver', [], 'ar') . ' [ ' . $driver->getName('ar') . ' ]',
			);

		}
		// return redirect()->back()->with('success',__('The Action Has Been Done'));
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
    }


}
