<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\StoreAddressRequest;
use App\Http\Resources\AddressesResource;
use App\Models\Address;
use App\Traits\Api\HasApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * * العناوين المفضلة ( المحفوظة للعميل)
 */
class AddressesController extends Controller
{
	use HasApiResponse;
    public function index(Request $request)
	{
		$user = $request->user('client') ;
		$addresses = QueryBuilder::for(Address::class)
		->where(function(Builder $builder) use ($request,$user){
			$builder->whereHas('client',function(Builder $builder) use ($request,$user){
				$builder->where($user->getTable().'.id',$user->id);
			});
		})
		->allowedSorts('id')
		->get() ;
		return  $this->apiResponse(__('Data Received Successfully',[],getApiLang()), AddressesResource::collection($addresses)->toArray($request));
	}
	public function store(StoreAddressRequest $request)
    {
		$address = $request->user('client')->addresses()->create(
			$request->only([
				'category','description','longitude','latitude'
			])
		);
		return  $this->apiResponse(__(':modelName Has Been Created Successfully',['modelName'=>__('Address',[],getApiLang())],getApiLang()),(new AddressesResource($address))->toArray($request));
    }
	public function update(StoreAddressRequest $request,Address $address)
    {
		$address->update(
			$request->only([
				'category','description','longitude','latitude'
			])
		);
		return  $this->apiResponse(__(':modelName Has Been Updated Successfully',['modelName'=>__('Address',[],getApiLang())],getApiLang()),(new AddressesResource($address))->toArray($request));
    }
	public function destroy(Address $address){
		$address->delete();
		return  $this->apiResponse(__(':modelName Has Been Deleted Successfully',['modelName'=>__('Address',[],getApiLang())],getApiLang()));
	}
	
}
