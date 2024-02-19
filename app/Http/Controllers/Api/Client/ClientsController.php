<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Notification;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
	use HasApiResponse;
	public function show(Request $request)
	{
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), (new ClientResource($request->user()))->toArray($request));
	}
	public function update(StoreClientRequest $request){
		/**
		 * @var Client $client
		 */
		$client = $request->user('client');
		$client->syncFromRequest($request);
		// Notification::storeNewNotification(
		// 	__('Profile Update', [], 'en'),
		// 	__('Profile Update', [], 'ar'),
		// 	$request->user('client')->getFullName('en') . ' ' . __('Has Updated His Profile Info', [], 'en') ,
		// 	$request->user('client')->getFullName('ar') . ' ' . __('Has Updated His Profile Info', [], 'ar') ,
		// );
		
		return $this->apiResponse(__('Your Profile Has Been Updated Successfully',[],getApiLang()));
	}
	
}
