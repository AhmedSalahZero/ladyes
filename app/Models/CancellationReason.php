<?php

namespace App\Models;

use App\Enum\CancellationReasonPhases;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasIsActive;
use App\Traits\Models\HasModelType;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
** شروط الالغاء ( سواء للعميل او للسائق زي مثلا ان السائق جه متاخر او العيل ماجاش الخ)
 */
class CancellationReason extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasTransNames,HasIsActive,HasModelType;

	public function getPhase()
	{
		return $this->phase ;
	}
	public function getPhaseFormatted()
	{
		return $this->phase ? CancellationReasonPhases::all()[$this->phase] : __('-') ;
	}
    public function syncFromRequest($request)
    {
		$this->phase = null ;
        if ($request->has('name_en')) {
            $this->name_en = $request->name_en;
        }
        if ($request->has('name_ar')) {
            $this->name_ar = $request->name_ar;
        }
		if ($request->has('model_type')) {
            $this->model_type = $request->model_type;
        }
		if ($request->has('phase') && $request->model_type == 'Client') {
            $this->phase = $request->phase;
        }
		$this->is_active = $request->boolean('is_active');
		
        $this->save();
    }
	public function travels()
	{
		return $this->hasMany(Travel::class,'cancellation_reason_id','id');
	}
}
