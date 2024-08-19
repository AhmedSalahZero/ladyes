<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\TravelConditionResource;
use App\Models\TravelCondition;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TravelConditionController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$travelConditions = QueryBuilder::for(TravelCondition::class)
		// ->where('model_type',HHelpers::getClassNameWithoutNameSpace($request->user()))
		->onlyIsActive()
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), TravelConditionResource::collection($travelConditions)->toArray($request));

	}
	
	
}
