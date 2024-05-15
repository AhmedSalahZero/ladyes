<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Driver;
use App\Models\Travel;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use Illuminate\Http\Resources\Json\JsonResource;

class CarSizeDriverResource extends JsonResource
{
	private static $city;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		/**
		 * @var Driver $closestDriver
		 */
		$closestDriver = $this->drivers->first() ;
		/**
		 * * ملحوظه احنا هنجيب بنجيب البيانات بالنسبة لاقرب سائق فقط علشان دا شكل الفريم في الموبايل 
		 */
		// 38.43567,21.94559,41.54480,21.02042
		// 30.00916,30.46525,33.11829,29.60451
		// dd
		$result = $closestDriver && $closestDriver->getLongitude() ? $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($closestDriver->getLatitude(),$closestDriver->getLongitude(),Request('to_latitude'),Request('to_longitude')) : [];
		/**
		 * @var City $city 
		 */
		$city = self::$city ;

		if(!$city){
			return [
			'car_size_id'=>$this->id ,
			'car_size_name'=>$this->getName(getApiLang()),
			'image'=>$this->getImage(),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'drivers_count'=>$this->drivers->count(),
			'price-range'=>'100 - 200' 
		];

		}
		$travel = new Travel ;
		// dd(now()->format('Y-m-d H:i:s'));
		$numberOfMinutesExpected = ($result['duration_in_seconds'] ?? 0) / 60;
		$distanceInKms = ($result['distance_in_meter']??0) / 1000 ;
		$sumPrice  = $this->getSumPrice($city->country->id);
		$expectedPrice = $travel->calculateClientActualPriceWithoutDiscount(now()->format('Y-m-d H:i:s'),$city,$closestDriver,$numberOfMinutesExpected,$distanceInKms);
		$expectedPrice = number_format($expectedPrice) . ' - ' . (number_format($expectedPrice+$sumPrice)) . ' '. $city->getCurrencyFormatted();
		
        return [
			'car_size_id'=>$this->id ,
			'car_size_name'=>$this->getName(getApiLang()),
			'image'=>$this->getImage(),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'drivers_count'=>$this->drivers->count(),
			'price-range'=>$expectedPrice 
		];
    }
	public static function customCollection($resource, $city): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
	 //you can add as many params as you want.
	  self::$city = $city;
	  return parent::collection($resource);
	}
}
