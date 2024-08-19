<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ShowCarModelRequest;
use App\Http\Resources\CarMakeResource;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;
use App\Traits\Api\HasApiResponse;
use Spatie\QueryBuilder\QueryBuilder;

class CarModelController extends Controller
{
	use HasApiResponse;
    public function index(ShowCarModelRequest $request)
	{
		$makeId = $request->get('make_id');
		$carModels = QueryBuilder::for(CarModel::class)
		->where('make_id',$makeId)
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CarModelResource::collection($carModels)->toArray($request));
	}
	
	
}
