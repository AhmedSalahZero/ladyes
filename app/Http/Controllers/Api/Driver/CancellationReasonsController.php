<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ShowCancellationReasonsRequest;
use App\Http\Resources\CancellationReasonResource;
use App\Models\CancellationReason;
use App\Traits\Api\HasApiResponse;
use Spatie\QueryBuilder\QueryBuilder;

class CancellationReasonsController extends Controller
{
	use HasApiResponse;
    public function index(ShowCancellationReasonsRequest $request)
	{
		$cancellationReasons = QueryBuilder::for(CancellationReason::class)
		->where('model_type',HHelpers::getClassNameWithoutNameSpace($request->user()))
		->when($request->has('phase'),function($builder) use ($request) {
			$builder->where('phase',$request->get('phase'));
		})
		->onlyIsActive()
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), CancellationReasonResource::collection($cancellationReasons)->toArray($request));
	}
	
	
}
