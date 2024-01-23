<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateAreasBasedOnCity extends Controller
{
    public function _invoke(Request $request){
		$city = City::find($request->get('city_id'));
		if($city){
			return response()->json([
				'status'=>Response::HTTP_OK,
				'msg'=>__('Data Received Successfully'),
				'data'=>AreaResource::collection($city->areas)
			]);
		}
	}
}
