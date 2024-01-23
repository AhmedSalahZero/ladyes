<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelResource;
use App\Http\Resources\CityResource;
use App\Models\CarMake;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateModelsBasedOnMake extends Controller
{
    public function _invoke(Request $request){
		$carMake = CarMake::find($request->get('make_id'));
		if($carMake){
			return response()->json([
				'status'=>Response::HTTP_OK,
				'msg'=>__('Data Received Successfully'),
				'data'=>CarModelResource::collection($carMake->models)
			]);
		}
	}
}
