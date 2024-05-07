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
	
}
