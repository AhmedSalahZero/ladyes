<?php

namespace App\Http\Controllers\Api\Client;

use App\Enum\AppNotificationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\MarkTravelAsCancelledRequest;
use App\Http\Requests\Apis\ShowAvailableCarSizesForTravelRequest;
use App\Http\Requests\Apis\ShowAvailableDriversForTravelRequest;
use App\Http\Requests\Apis\StoreTravelPaymentRequest;
use App\Http\Requests\Apis\StoreTravelRequest;
use App\Http\Resources\CarSizeDriverResource;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TravelResource;
use App\Models\CarSize;
use App\Models\City;
use App\Models\Driver;
use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
	use HasApiResponse;
    public function store(StoreTravelRequest $request)
	{
	
		$carSizeId = $request->get('car_size_id') ;
		$travel  = new Travel();
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		$fromLatitude = $request->get('from_latitude');
		$fromLongitude = $request->get('from_longitude');
		$toLatitude = $request->get('to_latitude');
		$toLongitude = $request->get('to_longitude');
		$travel->from_address_en =  $googleDistanceMatrixService->getFullAddressFromLatitudeAndLongitude($fromLatitude,$fromLongitude,'en');
		$travel->from_address_ar =  $googleDistanceMatrixService->getFullAddressFromLatitudeAndLongitude($fromLatitude,$fromLongitude,'ar');
		$travel->to_address_en =  $googleDistanceMatrixService->getFullAddressFromLatitudeAndLongitude($toLatitude,$toLongitude,'en');
		$travel->to_address_ar =  $googleDistanceMatrixService->getFullAddressFromLatitudeAndLongitude($toLatitude,$toLongitude,'ar');
		$travel->client_id = $request->user()->id ; 
		$city = $googleDistanceMatrixService->getCityFromLatitudeAndLongitude($request->user()->getCountry(),$fromLatitude,$fromLongitude);
		if(!$city){
			return $this->apiResponse(__('Your City Is Not Supported Yet',[],getApiLang()));
		}
		$travel->city_id = $city->id ;
		/**
		 * * علشان يتم انشاء اي دي للرحلة علشان لما السواق يجي يوافق علي الرحلة فا هحتاج الاي دي بتاعها ولو ما لقناش اي سواق يوافق هنحذفها وهي فاضية
		 */
		
		
		$travel = $travel->syncFromRequest($request);

		 $travel->sendRequestToAvailableDrivers($carSizeId); 
		
		 $travel->client->sendAppNotification(__('Travel Confirmation', [], 'en'), __('Travel Confirmation', [], 'ar'), __('We Will Contact You',[],'en'), __('We Will Contact You',[],'ar'), AppNotificationType::INFO);

		
		return $this->apiResponse(__('Travel Has Been Created Successfully',[],getApiLang()) , $travel->getResource()->toArray($request) );
	}
	public function markAsCancelled(MarkTravelAsCancelledRequest $request,int $travelId)
	{
		/**
		 * * دي لو مفيش رحلة .. بمعني انة لغى الرحلة قبل ما ينشئ الرحلة اكنه مثلا كان بيجرب او طلب بالغلط
		 */
		if($travelId == 0){
			if($request->get('cancellation_reason_id')){
				$request->user('client')->beforeTravelCancellationReasons()->attach($request->get('cancellation_reason_id'));
			}
			return $this->apiResponse(__('Travel Has Been Cancelled Successfully',[],getApiLang()),[],500);
		}
		$travel = Travel::find($travelId);
		$travel->markAsCancelled($request);
		return $this->apiResponse(__('Travel Has Been Marked AS Cancelled',[],getApiLang()));
	}
	public function storePayment(StoreTravelPaymentRequest $request,Travel $travel)
	{
		if(!$travel->hasEnded()){
			return $this->apiResponse(__('Travel Has Not Ended Yet',[],getApiLang()),[],500);
		}
		
		if($request->has('payment_method')){
			$travel->updatePaymentMethod($request->get('payment_method'));
		}
		$travel->storePayment($request);
		return $this->apiResponse(__('Thanks For Your Travel',[],getApiLang()),[
			'discount_coupon'=>$travel->getGiftCouponCode() 
		]);
	}
	public function sendArrivalNotificationToClient(Travel $travel)
	{
		$travel->client->sendAppNotification(__('Driver Arrival', [], 'en'), __('Driver Arrival', [], 'ar'), __('Driver Has Arrived To The Location',[],'en'), __('Driver Has Arrived To The Location'), AppNotificationType::LOCATION);
		return $this->apiResponse(__('Client Has Been Notified Of Your Arrival',[],getApiLang()));
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
		$carSizes = $carSizes->filter(function(CarSize $carSize){
			return $carSize->drivers->count();
		});
		if(!count($carSizes)){
			return  $this->apiResponse(__('No Available Drivers For Your Selected Location',[],getApiLang()),[],500);
		}
		$city  = GoogleDistanceMatrixService::getCityFromLatitudeAndLongitude(Request()->user('client')->getCountry(),Request('from_latitude'),Request('from_longitude'));
		if($city){
			return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarSizeDriverResource::customCollection($carSizes,$city)->toArray($request));
		}
		if(env('in_test_mode')){
			return $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarSizeDriverResource::collection($carSizes)->toArray($request));
		}
		return  $this->apiResponse(__('This City Is Not Support Yet',[],getApiLang()),[],500);
	}
	public function getPriceDetails(Request $request , Travel $travel){
		return $this->apiResponse(null , (new TravelResource($travel))->toArray($request),200);
	}
	
}
