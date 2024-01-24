<?php 
namespace App\Traits\Controllers;
use App\Helpers\HArr;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

trait Globals {
	public function paginate($items, $perPage = 5, $page = null, $options = [])
    {

		if(Request()->has('without_pagination') && Request()->boolean('without_pagination')){
			if(Request()->has('only_columns')){
				$onlyColumns = HArr::convertStringToArray(Request('only_columns'));
				$items = $items->map(function ($user) use($onlyColumns) {
					return collect($user)->only($onlyColumns);
				  });
			}
			return $items ; 
		}
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
		$dataPerPage = $items->forPage($page, $perPage)->values(); 
        return new LengthAwarePaginator($dataPerPage, $items->count(), $perPage, $page, $options);
    }
	public function deleteMultiple(array $ids , string $modelName,bool $forceDelete = false ){
		$model = new ('\App\Models\\'.$modelName) ;
		$deleteModel = $model->whereIn('id',$ids);
		if($forceDelete){
			$deleteModel->forceDelete();
		}else{
			$deleteModel->delete();
		}
		
	}
	public function restoreMultiple(array $ids , string $modelName ){
		$model = new ('\App\Models\\'.$modelName) ;
		 $model->whereIn('id',$ids)->restore();
	}
	public function syncRelation(string $type , array $mainIds , array $relationIds  , string $mainModelName , string $relationName)
    {
		$mainModel = new ('\App\Models\\'.$mainModelName);
		foreach($mainIds as $mainId){
			if($vendorDevice = ($mainModel)::find($mainId)){
				$vendorDevice->$relationName()->$type($relationIds);
			}
		}

    }
	public function getWebRedirectRoute(Request $request,string $indexRoute , string $createRoute){
			return response()->json([
				'status'=>true ,
				'message'=>__('msg.created_success'),
				'redirectTo'=>$request->save == 1 ? $createRoute :  $indexRoute
			]);
	}
}
