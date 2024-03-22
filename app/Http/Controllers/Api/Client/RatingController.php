<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreClientRatingRequest;
use App\Models\Driver;
use App\Traits\Api\HasApiResponse;
use App\Http\Requests\Apis\StoreDriverRatingRequest;

class RatingController extends Controller
{
	use HasApiResponse;
	/**
	 * * اضافة تقيم للسائق 
	 */
    public function rateWith(StoreDriverRatingRequest $request)
	{
		$rate = $request->get('rate',0);
		$driverId = $request->get('driver_id');
		$driver = Driver::find($driverId);
		$driver->rating([
			'rating' => $rate,
			'approved' => true, // This is optional and defaults to false
		], $request->user('client'));
		return  $this->apiResponse(__('Success Rating',[],getApiLang()),[]);
	}
}
