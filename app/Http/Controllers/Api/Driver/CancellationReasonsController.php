<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\CancellationReasonResource;
use App\Http\Resources\TravelConditionResource;
use App\Models\CancellationReason;
use App\Models\TravelCondition;
use App\Traits\Api\HasApiResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CancellationReasonsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$cancellationReasons = QueryBuilder::for(CancellationReason::class)
		->where('model_type',HHelpers::getClassNameWithoutNameSpace($request->user()))
		->onlyIsActive()
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CancellationReasonResource::collection($cancellationReasons)->toArray($request));
	}
	
	
}
