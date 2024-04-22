<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreTravelConditionRequest;
use App\Http\Resources\TravelConditionResource;
use App\Http\Resources\TravelConditionsResource;
use App\Models\TravelCondition;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TravelConditionsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$travelConditions = QueryBuilder::for(TravelCondition::class)
		->where(function(Builder $builder) use ($request,$user){
			$builder->whereHas($user->getTable(),function(Builder $builder) use ($request,$user){
				$builder->where($user->getTable().'.id',$user->id);
			});
		})
		->allowedSorts('id')
		->jsonPaginate() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), TravelConditionsResource::collection($travelConditions)->toArray($request));
	}
	/**
	 * * هنستخدم دي في الانشاء و التحديث بدلا من الانشاء فقط
	 * * بناء علي طلب مطور الموبايل
	 */
	public function store(StoreTravelConditionRequest $request)
    {
		$clientOrDriver = $request->user('client')?:$request->user('driver');
		TravelCondition::sync($clientOrDriver , $request->get('travel_condition_ids',[])  );
		return  $this->apiResponse(__(':modelName Has Been Updated Successfully',['modelName'=>__('Travel Conditions',[],getApiLang())],getApiLang()) , TravelConditionResource::collection($clientOrDriver->travelConditions)->toArray($request));
    }

	
}
