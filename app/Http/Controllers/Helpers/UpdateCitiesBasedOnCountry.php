<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateCitiesBasedOnCountry extends Controller
{
    public function _invoke(Request $request){
		$country = Country::find($request->get('country_id'));
		if($country){
			return response()->json([
				'status'=>Response::HTTP_OK,
				'msg'=>__('Data Received Successfully'),
				'data'=>CityResource::collection($country->cities)
			]);
		}
	}
}
