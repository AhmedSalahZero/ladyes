<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ShowCitiesRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Traits\Api\HasApiResponse;
use Spatie\QueryBuilder\QueryBuilder;

class CitiesController extends Controller
{
	use HasApiResponse;
    public function index(ShowCitiesRequest $request)
	{
		$countryId = $request->get('country_id');
		$countries = QueryBuilder::for(City::class)
		->where('country_id',$countryId)
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CityResource::collection($countries)->toArray($request));
	}
	
	
}
