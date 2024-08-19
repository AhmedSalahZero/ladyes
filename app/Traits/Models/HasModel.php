<?php 
namespace App\Traits\Models;

use App\Models\CarModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasModel
{
	public function model():BelongsTo
	{
		return $this->belongsTo(CarModel::class , 'model_id','id');
	}
	public function getModelName($lang):?string
	{
		$model = $this->model ;
		return $model ? $model->getName($lang) : null ;
	}
	public function getModelId():int 
	{
		$model = $this->model ;
		return $model ? $model->id : 0 ;
	}
}
