<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarMakeResource;
use App\Models\CarMake;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CarMakeController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$carMakes = QueryBuilder::for(CarMake::class)
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarMakeResource::collection($carMakes)->toArray($request));
	}
	
	
}
