<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionsResource;
use App\Models\Promotion;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PromotionsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$promotions = QueryBuilder::for(Promotion::class)
		->onlyAvailable()
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()),PromotionsResource::collection($promotions)->toArray($request));
	}
	
	
}
