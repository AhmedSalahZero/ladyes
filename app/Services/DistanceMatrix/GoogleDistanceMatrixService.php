<?php
namespace App\Services\DistanceMatrix;

use App\Models\Travel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class GoogleDistanceMatrixService
{
	/**
	 * * هنا بتجيبلك الوقت والمسافة ما بين السائق و العميل
	 */
	public function getExpectedArrivalTimeBetweenTwoPoints(string $fromLatitude , string $fromLongitude ,string $toLatitude , string $toLongitude )
	{

		$response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$fromLatitude.', '.$fromLongitude.'&destinations='.$toLatitude.', '.$toLongitude.'&key='.getSetting('google_api_key'));
		// dd($response->json());
		return [
			'distance_in_meter'=>Arr::get($response->json(),'rows.0.elements.0.distance.value',0),
			'duration_in_seconds'=>Arr::get($response->json(),'rows.0.elements.0.duration.value',0)
		];
	}
	/**
	 * * هنا بناخد لات ولانج .. ونص القطر
	 * * وهنجيب المناطق اللي بتبعد عن ال لات والانج دا بتلاثه كيلوا مثلا
	 * * علشان نجيب عدد الرحلات اللي حصلت في المنطقه دي
	 */
	public function findNearestRestaurants($latitude, $longitude, $radiusInKm = 3)
    {
        $travelsCount = Travel::selectRaw("id,from_latitude, from_longitude ,
		( 6371 * acos( cos( radians(?) ) *
		  cos( radians( from_latitude ) )
		  * cos( radians( from_longitude ) - radians(?)
		  ) + sin( radians(?) ) *
		  sin( radians( from_latitude ) ) )
		) AS distance", [$latitude, $longitude, $latitude])
		->having("distance", "<=", $radiusInKm)
		->orderBy("distance",'asc')
		->count();
        return $travelsCount;
    }
	
	 /**
	  * * ملحوظة :- انا هنا بستخدمة علشان اجيب فقط اسم المدينة اللي هي المقصود بيها هنا اسم المحافظة .. اما 
	  * * فعليا انت ممكن تستخدمة في اي شئ اخر
	  */
	  public function getCityNameFromLatitudeAndLongitude ($lat, $long) {
		// dd($lat,$long);
		$get_API = "https://maps.googleapis.com/maps/api/geocode/json?latlng=";
		$get_API .= $lat.",";
		$get_API .= $long;
		$get_API .= '&key='.getSetting('google_api_key');         
		// dd($get_API);
		$jsonfile = file_get_contents($get_API.'&sensor=false');
		$jsonarray = json_decode($jsonfile);        
		if($jsonarray){
			return $this->getCityName($jsonarray);
		}
		return false ;
		// if (isset($jsonarray->results[1]->address_components[1]->long_name)) {
		// 	return($jsonarray->results[1]->address_components[1]->long_name);
		// }
		// else {
		// 	return('Unknown');
		// }
	}
	/**
	 * * we will search for administrative_area_level_1 -->
	 */
	protected function getCityName($arr)
	{
		if(isset($arr->results[0]->address_components)){
			foreach($arr->results[0]->address_components as $address){
				if($address->types[0] == 'administrative_area_level_1'){
					return $address->long_name ;
				}
			}
		}
		return false ;
	}
	
	
}
