<?php

namespace App\Http\Controllers\Api\Driver;

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
		$travel = $travel->markAsStarted($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Started',[],getApiLang()));
	}
	public function markAsCancelled(Request $request,Travel $travel)
	{
		
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
		 $travel->driver_id = $request->user('driver')->id ;
		$travel->save();
		return $this->apiResponse(__('You Accept This Travel',[],getApiLang()));
	}
	
}
