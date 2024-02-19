<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarSizeResource;
use App\Models\CarSize;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CarSizesController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$carSizes = QueryBuilder::for(CarSize::class)
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarSizeResource::collection($carSizes)->toArray($request));
	}
	
	
}
