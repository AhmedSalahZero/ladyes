<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreSupportTicketRequest;
use App\Http\Resources\SupportTicketsResource;
use App\Http\Resources\TravelConditionResource;
use App\Models\SupportTicket;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SupportTicketsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$relationName = $request->user('client') ? 'client' : 'driver';
		$supportTickets = QueryBuilder::for(SupportTicket::class)
		->where(function(Builder $builder) use ($request,$user,$relationName){
			$builder->whereHas($relationName,function(Builder $builder) use ($request,$user,$relationName){
				$builder->where('model_id',$user->id);
			});
		})
		->allowedSorts('id')
		->jsonPaginate() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), SupportTicketsResource::collection($supportTickets)->toArray($request));
	}
	public function store(StoreSupportTicketRequest $request)
    {
		$clientOrDriver = $request->user('client')?:$request->user('driver');
		SupportTicket::create([
			'subject'=>$request->get('subject'),
			'message'=>$request->get('message'),
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($clientOrDriver),
			'model_id'=>$clientOrDriver->id 
		]);
		return  $this->apiResponse(__(':modelName Has Been Created Successfully',['modelName'=>__('Support Ticket',[],getApiLang())],getApiLang()) , TravelConditionResource::collection($clientOrDriver->travelConditions)->toArray($request));
    }

	
}
