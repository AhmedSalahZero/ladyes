<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateUsersBasedOnType extends Controller
{
    public function _invoke(Request $request){
		$fullClassName = '\App\Models\\'.$request->get('model_type') ;
		$users = $fullClassName::get();
		if(count($users)){
			return response()->json([
				'status'=>Response::HTTP_OK,
				'msg'=>__('Data Received Successfully'),
				'data'=>$users
			]);
		}
	}
}
