<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class DriversController extends Controller
{
	use HasApiResponse;
	public function show(Request $request)
	{
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), (new DriverResource($request->user()))->toArray($request));
	}
	public function update(StoreDriverRequest $request){
		$request->user('driver')->syncFromRequest($request);
		Notification::storeNewNotification(
			__('Profile Update', [], 'en'),
			__('Profile Update', [], 'ar'),
			$request->user('driver')->getFullName('en') . ' ' . __('Has Updated His Profile Info', [], 'en') ,
			$request->user('driver')->getFullName('ar') . ' ' . __('Has Updated His Profile Info', [], 'ar') ,
		);
		
		return $this->apiResponse(__('Your Profile Has Been Updated Successfully',[],getApiLang()));
	}
	
}
