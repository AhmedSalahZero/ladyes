<?php

namespace App\Http\Controllers\Api\Client;

use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\MarkTravelAsCompletedRequest;
use App\Http\Requests\Apis\ShowAvailableCarSizesForTravelRequest;
use App\Http\Requests\Apis\ShowAvailableDriversForTravelRequest;
use App\Http\Requests\Apis\StoreTravelPaymentRequest;
use App\Http\Requests\Apis\StoreTravelRequest;
use App\Http\Resources\CarSizeDriverResource;
use App\Http\Resources\DriverResource;
use App\Models\CarSize;
use App\Models\Driver;
use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;

class TravelsController extends Controller
{
	use HasApiResponse;
    public function store(StoreTravelRequest $request)
	{
		$travel  = new Travel();
		$travel = $travel->syncFromRequest($request);
	}
	public function storePayment(StoreTravelPaymentRequest $request,Travel $travel)
	{
		$travel->storePayment($request);
		return $this->apiResponse(__('Thanks For Your Travel',[],getApiLang()),[
			'discount_coupon'=>$travel->getGiftCouponCode() 
		]);
	}
	/**
	 * * هنجيب السواقين المتاحين 
	 */
	public function getAvailableDriverForTravels(ShowAvailableDriversForTravelRequest $request)
	{
		$latitude = $request->get('client_latitude');
		$longitude = $request->get('client_longitude');
		$carSizeId = $request->get('car_size_id');
		$drivers = Driver::getAvailableForSpecificLocationsAndCarSize($latitude,$longitude,$carSizeId);
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), DriverResource::collection($drivers)->toArray($request));
		
	}
	/**
	 * * دي فانكشن مساعدة بحيث لو حابب يجيب المسافة بين نقطتين علي الخريطة وليكن مثلا العميل والسائق و عايزين يجيب الوقع المتوقع للوصول
	 */
	public function getDistanceAndDurationBetweenClientAndDriver(ShowAvailableCarSizesForTravelRequest $request)
	{
		$fromLatitude = $request->get('from_latitude');
		$fromLongitude = $request->get('from_longitude');
		$toLatitude = $request->get('to_latitude');
		$toLongitude = $request->get('to_longitude');
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		$result = $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($fromLatitude,$fromLongitude,$toLatitude,$toLongitude);
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), $result);
	}
	/**
	 * * هنا هنجيب كل حجم  سيارة (برو و عائلي مثلا) ومعاة عدد السيارات المتاحة للمشوار دا والوقت المتوقع للوصول 
	 */
	public function getAvailableCarSizes(ShowAvailableCarSizesForTravelRequest $request)
	{
		$toLatitude = $request->get('to_latitude');
		$toLongitude = $request->get('to_longitude');
		$carSizes = CarSize::get()->each(function(CarSize $carSize) use($toLatitude,$toLongitude){
			$carSize->setRelation('drivers',Driver::getAvailableForSpecificLocationsAndCarSize($toLatitude,$toLongitude,$carSize->getId()));
		});
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarSizeDriverResource::collection($carSizes)->toArray($request));
	}
	
}
