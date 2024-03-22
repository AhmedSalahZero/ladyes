<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreClientRatingRequest;
use App\Models\Client;
use App\Traits\Api\HasApiResponse;

class RatingController extends Controller
{
	use HasApiResponse;
	/**
	 * * اضافة تقيم للعميل 
	 */
    public function rateWith(StoreClientRatingRequest $request)
	{
		$rate = $request->get('rate',0);
		$clientId = $request->get('client_id');
		$client = Client::find($clientId);
		$client->rating([
			'rating' => $rate,
			'approved' => true, // This is optional and defaults to false
		], $request->user('driver'));
		return  $this->apiResponse(__('Success Rating',[],getApiLang()),[]);
	}
}
