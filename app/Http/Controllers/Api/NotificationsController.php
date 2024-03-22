<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationsResource;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

/**
 * * عرض اشعاراتي سواء الاشعارات المبعوته من الادمن ليا او اللي السيستم بيبعتهالي تلقائي زي الغرامات مثلا 
 */
class NotificationsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$notifications = $user->notifications->sortByDesc('id');
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), NotificationsResource::collection($notifications)->toArray($request));
	}
	
	public function delete(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$user->notifications->each(function($notification){
			$notification->delete();
		});
		return  $this->apiResponse(__('Notification Removed Successfully',[],getApiLang()), []);
	}
	
	
}
