<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CountriesController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$countries = QueryBuilder::for(Country::class)
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CountryResource::collection($countries)->toArray($request));
	}
	
	
}
