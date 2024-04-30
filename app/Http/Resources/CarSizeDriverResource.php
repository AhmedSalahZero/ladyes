<?php

namespace App\Http\Resources;

use App\Models\Driver;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use Illuminate\Http\Resources\Json\JsonResource;

class CarSizeDriverResource extends JsonResource
{
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
		
		$result = $closestDriver && $closestDriver->getLongitude() ? $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($closestDriver->getLatitude(),$closestDriver->getLongitude(),Request('to_latitude'),Request('to_longitude')) : [];
		
        return [
			'car_size_id'=>$this->id ,
			'car_size_name'=>$this->getName(getApiLang()),
			'image'=>$this->getImage(),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'drivers_count'=>$this->drivers->count(),
			'price-range'=>'price_range'
		];
    }
}
