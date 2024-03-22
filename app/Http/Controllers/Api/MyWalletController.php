<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmergencyContactRequest;
use App\Http\Resources\TransactionsResource;
use App\Models\Client;
use App\Models\EmergencyContact;
use App\Models\Transaction;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * * عرض تفاصيل المعاملات المالية سواء دفع او غرامة او ايداع .. الخ
 * * transaction::class 
 */
class MyWalletController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$relationName = $user instanceof Client ? 'client' : 'driver';  
		$transactions = QueryBuilder::for(Transaction::class)
		->where(function(Builder $builder) use ($request,$user,$relationName){
			$builder->whereHas($relationName,function(Builder $builder) use ($request,$user){
				$builder->where($user->getTable().'.id',$user->id);
			});
		})
		->allowedSorts('id')
		->jsonPaginate() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), TransactionsResource::collection($transactions)->toArray($request));
	}
	public function store(StoreEmergencyContactRequest $request)
    {
        $model = new EmergencyContact();
        $model->syncFromRequest($request);
		$clientOrDriver = $request->user('client')?:$request->user('driver');
		EmergencyContact::sync($clientOrDriver , $model->id , $request->boolean('can_receive_travel_info'), true );
		return  $this->apiResponse(__(':modelName Has Been Created Successfully',['modelName'=>__('Emergency Contact',[],getApiLang())],getApiLang()));
    }
	
	
}
