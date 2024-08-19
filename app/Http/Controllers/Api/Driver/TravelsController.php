<?php

namespace App\Http\Controllers\Api\Driver;

use App\Enum\AppNotificationType;
use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
	use HasApiResponse;

	/**
	 * * هنا بنحدد الوقت اللي السواق فيه وصل للمكان المتفق عليه
	 */
	public function markAsArrived(Request $request,Travel $travel)
	{
		$travel = $travel->markAsArrived($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Arrived',[],getApiLang()));
	}
	/**
	 * * هنا بنحدد الوقت الفعلي اللي الرحلة بدات فيه بحيث لو العميل اتاخر بنديله غرامة
	 */
	public function markAsStarted(Request $request,Travel $travel)
	{
		if(!$travel->driver){
			return $this->apiResponse(__('No Driver Accept This Travel Yet'));
		}
		$travel = $travel->markAsStarted($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Started',[],getApiLang()));
	}
	public function markAsCancelled(Request $request,Travel $travel)
	{
		if(!$travel->driver){
			return $this->apiResponse(__('No Driver Accept This Travel Yet'));
		}
		// if(!$travel->hasStarted()){
		// 	return $this->apiResponse(__('Travel Has Not Started Yet',[],getApiLang()),[],500);
		// }
		$travel->markAsCancelled($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Cancelled',[],getApiLang()));
	}
	public function markAsCompleted(Request $request,Travel $travel)
	{ 
		
		if(!$travel->hasStarted()){
			return $this->apiResponse(__('Travel Has Not Started Yet',[],getApiLang()),[],500);
		}
		$travel = $travel->markAsCompleted($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Completed',[],getApiLang()));
	}
	/**
	 * * من هنا السواق هيوافق انه يشتغل علي الرحلة دي
	 */
	public function accept(Request $request,Travel $travel)
	{ 
		$driver = $request->user('driver');
		 $travel->driver_id = $driver->id ;
		$travel->save();
		$travel->client->sendAppNotification(__('Driver Accept Your Travel Request',[],'en'),__('Driver Accept Your Travel Request',[],'ar'),
				__('Driver :driverName Has Accept Your Travel Request',['driverName'=>$driver->getName()],'en'),
				__('Driver :driverName Has Accept Your Travel Request',['driverName'=>$driver->getName()],'ar'),
				AppNotificationType::INFO,null,'notification'
			);
	
		$travel->client->sendDriverAccept($driver);
		
		return $this->apiResponse(__('You Accept This Travel',[],getApiLang()));
	}
	
}
