<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreEmergencyContactRequest;
use App\Http\Resources\EmergencyContactsResource;
use App\Models\EmergencyContact;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EmergencyContactsController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ?: $request->user('driver');
		$emergencyContacts = QueryBuilder::for(EmergencyContact::class)
		->where(function(Builder $builder) use ($request,$user){
			$builder->whereHas($user->getTable(),function(Builder $builder) use ($request,$user){
				$builder->where($user->getTable().'.id',$user->id);
			});
		})
		->allowedSorts('id')
		->jsonPaginate() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), EmergencyContactsResource::collection($emergencyContacts)->toArray($request));
	}
	/**
	 * * هنستخدم دي في الانشاء و التحديث بدلا من الانشاء فقط
	 * * بناء علي طلب مطور الموبايل
	 */
	public function store(StoreEmergencyContactRequest $request)
    {
		$clientOrDriver = $request->user('client')?:$request->user('driver');
        $model = $clientOrDriver->emergencyContacts->first() ?:  new EmergencyContact();
        $model->syncFromRequest($request);
		EmergencyContact::sync($clientOrDriver , $model->id , $request->boolean('can_receive_travel_info'), true );
		return  $this->apiResponse(__(':modelName Has Been Created Successfully',['modelName'=>__('Emergency Contact',[],getApiLang())],getApiLang()) , (new EmergencyContactsResource($model))->toArray($request));
    }
	// public function update(StoreEmergencyContactRequest $request,EmergencyContact $emergencyContact)
    // {
	// 	$clientOrDriver = $request->user('client')?:$request->user('driver');
	// 	$emergencyContact->syncFromRequest($request);
	// 	EmergencyContact::sync($clientOrDriver , $emergencyContact->id , $request->boolean('can_receive_travel_info'), true );
	// 	return  $this->apiResponse(__(':modelName Has Been Updated Successfully',['modelName'=>__('Emergency Contact',[],getApiLang())],getApiLang()));
    // }
	public function destroy(EmergencyContact $emergencyContact){
		$emergencyContact->delete();
		return  $this->apiResponse(__(':modelName Has Been Deleted Successfully',['modelName'=>__('Emergency Contact',[],getApiLang())],getApiLang()));
	}
	
}
