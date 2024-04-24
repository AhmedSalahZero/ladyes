<?php
namespace App\Services\DistanceMatrix;

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
			'distance_in_meter'=>Arr::get($response->json(),'rows.0.elements.0.distance.value',0)  ,
			'duration_in_seconds'=>Arr::get($response->json(),'rows.0.elements.0.duration.value',0)
		];
	}
}
