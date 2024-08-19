<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponsResource;
use App\Models\Coupon;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CouponsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$coupons = QueryBuilder::for(Coupon::class)
		->onlyAvailable()
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()),CouponsResource::collection($coupons)->toArray($request));
	}
	
	
}
